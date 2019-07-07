<?php

namespace Modules\Translates\Facades;

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
        $module_name = giveMeTheModuleName(static::class, null);
        $settings = \Config::get('module_'.strtolower($module_name), null);
        if (!empty($settings)) {
            if (isset($settings['FacadeName']) && !\is_array($settings['FacadeName'])) {
                $returned = $settings['FacadeName'];
            }

            if (isset($settings['FacadeName']['alias']) && \is_array($settings['FacadeName'])) {
                $returned = $settings['FacadeName']['alias'];
            }
        }

        return $returned;
    }
}
