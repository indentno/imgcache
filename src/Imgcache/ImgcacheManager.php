<?php

namespace Indent\Imgcache\Imgcache;

use Indent\Imgcache\Drivers\Cloudinary;
use Indent\Imgcache\Drivers\Contracts\DriverInterface;

class ImgcacheManager
{
    public function driver(string|null $driver = null): DriverInterface
    {
        return match ($driver) {
            'cloudinary' => new Cloudinary,
            default => new Cloudinary,
        };
    }

    public function make(string $source): DriverInterface
    {
        return $this->driver()->make($source);
    }

    public function __call(string $method, array $parameters): mixed
    {
        return $this->driver()->$method(...$parameters);
    }
}
