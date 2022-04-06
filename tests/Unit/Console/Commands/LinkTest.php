<?php

namespace Tests\Unit\Console\Commands;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Indent\Imgcache\Fascades\Imgcache;
use Tests\BaseTest;

class LinkTest extends BaseTest
{
    private string $imgcachePath;

    public function setUp(): void
    {
        parent::setUp();

        $this->imgcachePath = public_path('imgcache');

        if (File::exists($this->imgcachePath)) {
            unlink($this->imgcachePath);
        }

        $this->assertFalse(File::exists($this->imgcachePath));
        $this->assertEmpty(File::directories(public_path()));
    }

    public function testCanCreateSymlink()
    {
        Artisan::call('imgcache:link');

        $this->assertTrue(File::exists($this->imgcachePath));
        $this->assertNotEmpty(File::directories(public_path()));
    }

    public function testCanAccesFilesThroughSymlink()
    {
        Artisan::call('imgcache:link');
        Imgcache::make('https://picsum.photos/id/1/100/100')->get();

        $this->assertTrue(File::exists($this->imgcachePath));
        $this->assertNotEmpty(File::directories(public_path()));

        $files = File::allFiles($this->imgcachePath);
        $this->assertStringContainsString('/public/imgcache', $files[0]->getPath());
        
        $this->assertStringNotContainsString('/public/imgcache', $files[0]->getRealPath());
        $this->assertStringContainsString('/storage/imgcache', $files[0]->getRealPath());
    }

    public function testImgcacheShouldContainGitignore()
    {
        Artisan::call('imgcache:link');

        $this->assertSame('.gitignore', File::allFiles($this->imgcachePath, true)[0]->getRelativePathname());
    }

    public function tearDown(): void
    {
        parent::tearDown();

        unlink($this->imgcachePath);
    }
}
