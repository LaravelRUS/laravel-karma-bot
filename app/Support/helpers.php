<?php
// Asset with timestamp
if (!function_exists('asset_ts')) {
    function asset_ts($path, $secure = false)
    {
        $url = asset($path, $secure);

        if (is_file(public_path($path))) {
            $time = filemtime(public_path($path));
            $url .= '?' . $time;
        }

        return $url;
    }
}