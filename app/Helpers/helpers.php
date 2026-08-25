<?php

use App\Models\Setting;

if (!function_exists('settings')) {
    function settings($key = null, $default = null)
    {
        static $settings = null;

        if ($settings === null) {
            $settings = Setting::query()->first();
        }

        if (!$settings) {
            return $default;
        }

        return $key ? ($settings->{$key} ?? $default) : $settings;
    }
}