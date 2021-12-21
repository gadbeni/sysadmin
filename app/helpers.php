<?php
if (!function_exists('set_setting')) {
    function set_setting($key, $value = null){
        try {
            \DB::table('settings')->where('key', $key)->update(['value' => $value]);
            return true;
        } catch (\Throwable $th) {
            //throw $th;
            return false;
        }
    }
}