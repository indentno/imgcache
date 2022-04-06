<?php

namespace Tests\Unit\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Indent\Imgcache\Console\Commands\ClearCache;
use Indent\Imgcache\Fascades\Imgcache;
use Tests\BaseTest;

class ClearCacheTest extends BaseTest
{
    public function testCanClearCache()
    {
        Imgcache::make('https://picsum.photos/id/1/100/100')->get();
        Imgcache::make('https://picsum.photos/id/2/100/100')->get();
        Imgcache::make('https://picsum.photos/id/3/100/100')->get();
        $this->assertImgcacheFileCount(3);
        
        Artisan::call(ClearCache::class);
        $this->assertImgcacheFileCount(0);
    }

    public function testCanBeCalledWithSignature()
    {
        Imgcache::make('https://picsum.photos/id/1/100/100')->get();
        $this->assertImgcacheFileCount(1);
        
        Artisan::call('imgcache:clear');
        $this->assertImgcacheFileCount(0);
    }
}
