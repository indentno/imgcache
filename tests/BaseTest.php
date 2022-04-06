<?php

namespace Tests;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;
use Orchestra\Testbench\TestCase as OrchestraTestCase;

abstract class BaseTest extends OrchestraTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        
        config(['services.imgcache.cloudinary.cloud_name' => 'demo']);

        Artisan::call('imgcache:clear');
        $this->assertImgcacheFileCount(0);
    }

    protected function assertImgcacheFileCount(int $count)
    {
        $this->assertCount($count, File::allFiles(storage_path('imgcache')));
    }

    protected function getEnvironmentSetUp($app)
    {
        $basePath = realpath(__DIR__ . '/..');
        $app->setBasePath($basePath);
    }

    protected function getPackageProviders($app)
    {
        return ['Indent\Imgcache\Providers\ServiceProvider'];
    }
}
