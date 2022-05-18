<?php

namespace Tests\Unit;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Indent\Imgcache\Fascades\Imgcache;
use Tests\BaseTest;

class CloudinaryTest extends BaseTest
{
    public function testUnmodifiedFile()
    {
        Imgcache::make('https://picsum.photos/id/1/100/100')->get();
        $this->assertImgcacheFileCount(1);
    }

    public function testReturnsRelativeUrlToImage()
    {
        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->get();

        $this->assertStringContainsString('/imgcache/', $string);
        $this->assertStringNotContainsString('storage', $string);
    }

    public function testModifiers()
    {
        $source = 'https://picsum.photos/id/1/100/100';

        Imgcache::make($source)->blur(rand(1, 2000))->get();
        $this->assertImgcacheFileCount(1);

        Imgcache::make($source)->brightness(rand(-99, 100))->get();
        $this->assertImgcacheFileCount(2);

        Imgcache::make($source)->crop(rand(50, 200), rand(50, 200))->get();
        $this->assertImgcacheFileCount(3);

        Imgcache::make($source)->pixelate(rand(1, 200))->get();
        $this->assertImgcacheFileCount(4);

        Imgcache::make($source)->height(rand(50, 200))->get();
        $this->assertImgcacheFileCount(5);
        
        Imgcache::make($source)->width(rand(50, 200))->get();
        $this->assertImgcacheFileCount(6);

        Imgcache::make($source)->format(Arr::random(['png', 'avif', 'webp']))->get();
        $this->assertImgcacheFileCount(7);
    }

    public function testRetrievesFileFromCache()
    {
        Imgcache::make('https://picsum.photos/id/1/100/100')->get();
        $this->assertImgcacheFileCount(1);
        
        Imgcache::make('https://picsum.photos/id/1/100/100')->get();
        $this->assertImgcacheFileCount(1);
    }

    public function testShouldUpdateExtensionWhenSettingFormat()
    {
        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->get();
        $this->assertStringContainsString('.jpg', $string);
        $this->assertImgcacheFileCount(1);

        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->format('jpg')->get();
        $this->assertStringContainsString('.jpg', $string);
        $this->assertImgcacheFileCount(2);

        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->format('png')->get();
        $this->assertStringContainsString('.png', $string);
        $this->assertImgcacheFileCount(3);

        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->format('gif')->get();
        $this->assertStringContainsString('.gif', $string);
        $this->assertImgcacheFileCount(4);

        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->format('avif')->get();
        $this->assertStringContainsString('.avif', $string);
        $this->assertImgcacheFileCount(5);
        
        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->format('webp')->get();
        $this->assertStringContainsString('.webp', $string);
        $this->assertImgcacheFileCount(6);
    }

    public function testBase64()
    {
        $string = Imgcache::make('https://picsum.photos/id/1/100/100')->base64();
        $this->assertImgcacheFileCount(1);

        $base64 = Str::afterLast($string, ',');
        $this->assertTrue(base64_encode(base64_decode($base64, true)) === $base64);
        $this->assertStringContainsString('base64', $string);
    }

    public function testTinyBase64()
    {
        $string = Imgcache::make('https://picsum.photos/id/' . rand(1, 10) . '/100/100')
            ->width(50)
            ->blur(2000)
            ->format('webp')
            ->base64();

        $this->assertTrue(strlen($string) < 300);
        $this->assertImgcacheFileCount(1);

        $base64 = Str::afterLast($string, ',');
        $this->assertTrue(base64_encode(base64_decode($base64, true)) === $base64);
        $this->assertStringContainsString('base64', $string);
    }

    public function testCanUseExistingCachedImageWhenReturningBase64()
    {
        Imgcache::make('https://picsum.photos/id/1/100/100')->get();
        $this->assertImgcacheFileCount(1);

        Imgcache::make('https://picsum.photos/id/1/100/100')->base64();
        $this->assertImgcacheFileCount(1);

        Imgcache::make('https://picsum.photos/id/1/100/100')->blur(1000)->get();
        $this->assertImgcacheFileCount(2);

        Imgcache::make('https://picsum.photos/id/1/100/100')->blur(1000)->base64();
        $this->assertImgcacheFileCount(2);
    }

    public function testBase64ContainsFormat()
    {
        $format = [
            'png' => 'png',
            'jpg' => 'jpeg',
            'gif' => 'gif',
            'avif' => 'avif',
            'webp' => 'webp',
        ];

        $i = 1;

        foreach ($format as $extension => $mime) {
            $string = Imgcache::make('https://picsum.photos/id/1/100/100')->format($extension)->base64();
            $this->assertImgcacheFileCount(($i));
        
            $this->assertStringContainsString('base64', $string);
            $this->assertStringContainsString($mime, $string);
            $i++;
        }
    }

    public function testSavesErrorImageWhenFail()
    {
        $url = 'https://fake-url-xoxo.com/fake-img.jpg';
        
        $path = Imgcache::make($url)->width(100)->get();

        $this->assertImgcacheFileCount(1);

        $this->assertSame(
            file_get_contents(__DIR__ . '/../../resources/img/error.png'),
            file_get_contents(storage_path($path)),
        );
    }
}
