<?php

namespace Indent\Imgcache\Fascades;

use Illuminate\Support\Facades\Facade;

/**
 * @method static \Indent\Imgcache\Drivers\Contracts\DriverInterface driver(string $driver = null)
 * @method static \Indent\Imgcache\Drivers\Contracts\DriverInterface make(string $source)
 *
 * @see \Indent\Imgcache\ImgcacheManager
 */
class Imgcache extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'indent.imgcache';
    }
}
