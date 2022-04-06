<?php

namespace Tests\Unit;

use Tests\BaseTest;

class HelperTest extends BaseTest
{
    public function testGlobalHelperFunction()
    {
        imgcache('https://picsum.photos/id/1/100/100')->get();
        $this->assertImgcacheFileCount(1);
    }

    public function testGlobalHelperFunctionCanCallModifiers()
    {
        imgcache('https://picsum.photos/id/1/100/100')->blur(2000)->width(80)->get();
        $this->assertImgcacheFileCount(1);
    }

    public function testReturnsObjectWithoutCachingWhenNotCastedToString()
    {
        $this->assertIsObject(imgcache('https://picsum.photos/id/1/100/100'));
        $this->assertImgcacheFileCount(0);
    }

    public function testGlobalHelperFunctionImplementsStringable()
    {
        $this->assertStringContainsString(
            '/imgcache/',
            (string) imgcache('https://picsum.photos/id/1/100/100')
        );
        $this->assertImgcacheFileCount(1);
    }
}
