<?php

namespace GeekCms\Translates\Repository;

use Illuminate\Filesystem\Filesystem;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use GeekCms\Translates\Models\TranslateLanguages;

class ManagerRepository
{
    /**
     * @var null|self
     */
    private static $_instance;

    /**
     * @var Collection
     */
    private $storage;

    /**
     * Contain array with translates aliases.
     *
     * @var array
     */
    private $storage_keys;

    /**
     * @var string
     */
    private $cacheTranslatesKey = 'translates.manager.cached';
    private $cacheCheckTranslatesKey = 'translates.check.cached';

    /**
     * Instance.
     *
     * @return null|self
     */
    public static function getInstance()
    {
        if (!isset(self::$_instance)) {
            self::$_instance = new static();
            self::$_instance->init();
        }

        return self::$_instance;
    }

    /**
     * Init.
     */
    public function init()
    {
        $this->storage = $this->getTranslates(true);
    }

    /**
     * Get cached translates.
     *
     * @param bool $clear
     *
     * @throws \Exception
     *
     * @return mixed
     */
    public function getTranslates($clear = false)
    {
        $returned = $this->storage;
        if (!\is_array($returned) || !\count($returned)) {
            $returned = $this->getTranslatesDb();
        }

        return $returned;
    }

    /**
     * Get available locales list.
     *
     * @throws \Exception
     *
     * @return array
     */
    public function getLocales()
    {
        if ($this->checkTranslateTable()) {
            $translates = $this->getTranslatesCachedQuery();

            return (\count($translates)) ? $translates->keyBy('key')->toArray() : [];
        }

        return [];
    }

    /**
     * Get translates alias with lang and module name.
     *
     * @return array|Collection
     */
    public function getTranslatesKeys()
    {
        if (!$this->storage_keys instanceof Collection || !$this->storage_keys->count()) {
            $this->storage_keys = $this->getTranslatesCollect();
        }

        return $this->storage_keys;
    }

    /**
     * Get translate.
     *
     * @param $key
     * @param array $replace
     * @param null  $lang
     *
     * @return null|array|\Illuminate\Contracts\Translation\Translator|mixed|string
     */
    public function get($key, $replace = [], $lang = null)
    {
        $current_lang = \App::getLocale();
        $prepared = $this->getTranslatesDb();
        $lang = (empty($lang) || !isset($prepared[$lang])) ? $current_lang : $lang;

        return (!isset($prepared[$lang][$key])) ? trans($key, $replace, $lang) : $prepared[$lang][$key];
    }

    /**
     * Method for set translates from lang files.
     *
     * @param $path
     * @param null $prefix
     *
     * @return array
     */
    public function setTranslatesByPath($path, $prefix = null)
    {
        $languages_data = [];
        $prefix = (empty($prefix)) ? 'main' : $prefix;
        $filesystem = new Filesystem();

        if ($filesystem->exists($path)) {
            $files = $filesystem->allFiles($path);

            foreach ($files as $file) {
                $pathinfo = pathinfo($file);
                $short = preg_replace('/'.preg_quote($path, '/').'/sui', '', $pathinfo['dirname']);
                $path_split = array_filter(explode('/', $short));
                $path_split = array_values($path_split);
                krsort($path_split);
                $lang_code = $path_split[0];
                unset($path_split[0]);
                $path_split = implode('/', $path_split);
                $path_split = (!empty($path_split)) ? $path_split.'/' : $path_split;
                $translates = require_once $file;

                if (\is_array($translates)) {
                    $languages_data[$lang_code][$prefix][$path_split.$pathinfo['filename']] = Arr::dot($translates);
                }
            }
        }

        return $languages_data;
    }

    /**
     * Get translates list.
     *
     * @throws \Exception
     *
     * @return Collection
     */
    protected function getTranslatesCollect()
    {
        $modules = \Module::all();
        $current_lang = \App::getLocale();
        $load_langs = resource_path('lang');
        $languages_data = $this->setTranslatesByPath($load_langs);

        foreach ($modules as $module) {
            $load_langs = module_path($module->name).'/Resources/lang';
            $loaded = $this->setTranslatesByPath($load_langs, 'module_'.strtolower($module->name));
            if (\count($loaded)) {
                $languages_data = array_merge_recursive($languages_data, $loaded);
            }
        }

        if ($this->checkTranslateTable()) {
            $languages = $this->getTranslatesCachedQuery();

            foreach ($languages as $lang) {
                if (!isset($languages_data[$lang->key])) {
                    $languages_data[$lang->key] = $languages_data[$current_lang];
                }
            }
        }

        return collect($languages_data);
    }

    /**
     * Get translates from DB.
     *
     * @throws \Exception
     *
     * @return array
     */
    protected function getTranslatesDb()
    {
        $prepared_data = [];

        if (!$this->checkTranslateTable()) {
            return $prepared_data;
        }

        if (!$this->storage_keys instanceof Collection || !$this->storage_keys->count()) {
            $this->storage_keys = $this->getTranslatesCollect();
        }

        $languages = $this->getTranslatesCachedQuery();
        foreach ($languages as $lang) {
            foreach ($lang->items as $translate) {
                $prepared_data[$lang->key][$translate->key] = $translate->translate;
            }
        }

        foreach ($this->storage_keys->all() as $lang_key => $langs) {
            foreach ($langs as $translate_key => $translate_val) {
                foreach ($translate_val as $main_key => $main_val) {
                    foreach ($main_val as $tr_key => $tr_val) {
                        $folders = $main_key.'.'.$tr_key;
                        $main_key_mod = ('main' !== $translate_key) ? $translate_key.'::'.$folders : $folders;
                        if (!isset($prepared_data[$lang_key], $prepared_data[$lang_key][$main_key_mod])) {
                            $prepared_data[$lang_key][$main_key_mod] = $tr_val;
                        }
                    }
                }
            }
        }

        return $prepared_data;
    }

    /**
     * Get translates table data with cache.
     *
     * @param bool $clear
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function getTranslatesCachedQuery($clear = false)
    {
        // clear cache
        if ($clear) {
            if (Cache::has($this->cacheTranslatesKey)) {
                $try_remove = Cache::forget($this->cacheTranslatesKey);
                if (!$try_remove) {
                    throw new \Exception('Bladskiy cache for lang');
                }
            }
        }

        return Cache::remember($this->cacheTranslatesKey, config('cache.settings.minutes', 10), function () {
            try {
                return TranslateLanguages::with('items')->get();
            } catch (\Illuminate\Database\QueryException $e) {
                return collect();
            }
        });
    }

    /**
     * Check if table translates isset and cache result.
     *
     * @param bool $clear
     *
     * @throws \Exception
     *
     * @return mixed
     */
    private function checkTranslateTable($clear = false)
    {
        // clear cache
        if ($clear) {
            if (Cache::has($this->cacheCheckTranslatesKey)) {
                $try_remove = Cache::forget($this->cacheCheckTranslatesKey);
                if (!$try_remove) {
                    throw new \Exception('Bladskiy cache for lang and check table');
                }
            }
        }

        return Cache::remember($this->cacheCheckTranslatesKey, config('cache.settings.minutes', 10), function () {
            try {
                return Schema::hasTable(TranslateLanguages::tablename());
            } catch (\Illuminate\Database\QueryException $e) {
                return false;
            }
        });
    }
}
