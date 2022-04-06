<?php

if (!function_exists('imgcache')) {
    function imgcache(string $source)
    {
        return app('indent.imgcache')->make($source);
    }
}
