<?php

namespace GeekCms\Translates\Http\Controllers;

use Config;
use Exception;
use GeekCms\PackagesManager\Facades\PackageSystem;
use GeekCms\Translates\Models\TranslateLanguages;
use GeekCms\Translates\Models\TranslateLanguagesElements;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\View\View;
use Module;
use Translate;
use function count;

class AdminController extends Controller
{
    /**
     * Page with main languages.
     *
     * @return Factory|View
     */
    public function index()
    {
        $languages = TranslateLanguages::all();

        return view('translates::admin.index', [
            'elements' => $languages,
        ]);
    }

    /**
     * Page with list translates for selected language.
     *
     * @param TranslateLanguages $lang
     * @param Request $request
     *
     * @return Factory|View
     * @throws Exception
     * @throws Exception
     */
    public function list(TranslateLanguages $lang, Request $request)
    {
        $languages = TranslateLanguages::all();
        $current_language = $lang;
        $local_modules = PackageSystem::all();
        $translates_factory = Translate::getInstance();
        $translates = $translates_factory->getTranslates();
        $translates_keys = $translates_factory->getTranslatesKeys();

        return view('translates::admin.list', [
            'languages' => $languages,
            'current_language' => $current_language,
            'modules' => $local_modules,
            'translates' => $translates,
            'translates_keys' => $translates_keys,
        ]);
    }

    /**
     * Page for edit language.
     *
     * @param TranslateLanguages $lang
     * @param Request $request
     *
     * @return Factory|View
     */
    public function edit(TranslateLanguages $lang, Request $request)
    {
        $posts = $request->all();
        $route = route('admin.translates.edit', ['lang' => $lang->id ?? null]);

        if (class_exists('Config')) {
            $flags = Config::get('module_translates.flags_list', []);
        } else {
            $flags = config('module_translates.flags_list', []);
        }

        if ($request->isMethod('post')) {
            if ($lang->fill($posts) && !$lang->validate($posts)->fails()) {
                $lang->save();
                \Translate::clearCache();
                return redirect()->route('admin.translates');
            }
        }

        return view('translates::admin.update', [
            'route' => $route,
            'flags' => $flags,
            'item' => $lang,
        ]);
    }

    /**
     * Page for create language.
     *
     * @param Request $request
     *
     * @return Factory|View
     */
    public function create(Request $request)
    {
        $route = route('admin.translates.create');
        $lang_model = new TranslateLanguages();
        $posts = $request->all();

        if (class_exists('Config')) {
            $flags = Config::get('module_translates.flags_list', []);
        } else {
            $flags = config('module_translates.flags_list', []);
        }

        if ($request->isMethod('post')) {
            if ($lang_model->fill($posts) && !$lang_model->validate($posts)->fails()) {
                $lang_model->save();
                \Translate::clearCache();

                return redirect()->route('admin.translates');
            }
        }

        return view('translates::admin.update', [
            'route' => $route,
            'flags' => $flags,
        ]);
    }

    /**
     * Remove selected language.
     *
     * @param TranslateLanguages $lang
     * @param Request $request
     *
     * @return RedirectResponse
     * @throws Exception
     *
     */
    public function delete(TranslateLanguages $lang, Request $request)
    {
        $lang->delete();
        $lang->items()->delete();
        \Translate::clearCache();

        return redirect()->route('admin.translates');
    }

    /**
     * Remove more one selected languages.
     *
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function deleteAll(Request $request)
    {
        $get_translates = $request->get('items', '');
        $get_translates = explode(',', $get_translates);
        $get_translates = array_filter($get_translates);

        if (count($get_translates)) {
            $find_translate = TranslateLanguages::whereIn('id', $get_translates);
            if ($find_translate->count()) {
                foreach ($find_translate->get() as $ftranslate) {
                    $ftranslate->items()->delete();
                }
                $find_translate->delete();
                \Translate::clearCache();
            }
        }

        return redirect()->route('admin.translates');
    }

    /**
     * Save language translates.
     *
     * @param TranslateLanguages $lang
     * @param Request $request
     *
     * @return RedirectResponse
     */
    public function save(TranslateLanguages $lang, Request $request)
    {
        $update_list = [];
        $toupdate = $request->post('translates', []);

        if ($lang && !empty($lang->id)) {
            $items_list = $lang->items();
            foreach ($toupdate as $tkey => $val) {
                $update_list[] = new TranslateLanguagesElements(['lang_id' => $lang->id, 'key' => $tkey, 'translate' => $val]);
            }

            if (count($update_list)) {
                $items_list->delete();
                $items_list->saveMany($update_list);
                \Translate::clearCache();
            }
        }

        return redirect()->back();
    }
}
