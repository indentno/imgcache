<?php

namespace Indent\Imgcache\Providers;

use Illuminate\Support\ServiceProvider as Provider;
use Indent\Imgcache\Console\Commands\ClearCache;
use Indent\Imgcache\Console\Commands\Link;
use Indent\Imgcache\Imgcache\ImgcacheManager;

class ServiceProvider extends Provider
{
    public function register()
    {
        require_once __DIR__ . '/helpers.php';

        $this->app->bind('indent.imgcache', ImgcacheManager::class);
    }

    public function boot()
    {
        $this->commands([
            ClearCache::class,
            Link::class,
        ]);
    }
}
