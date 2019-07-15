<?php

namespace GeekCms\Translates\Facades;

use Config;
use Gcms;
use function is_array;

class Facade extends \Illuminate\Support\Facades\Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        $returned = null;
        $module_name = module_package_name(static::class, null);
        $settings = Config::get(config('modules.module_prefix') . strtolower($module_name), null);
        if (!empty($settings)) {
            if (isset($settings['FacadeName']) && !is_array($settings['FacadeName'])) {
                $returned = $settings['FacadeName'];
            }

            if (isset($settings['FacadeName']['alias']) && is_array($settings['FacadeName'])) {
                $returned = $settings['FacadeName']['alias'];
            }
        }

        return $returned;
    }
}
