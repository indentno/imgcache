<?php

namespace Indent\Imgcache\Drivers;

use Exception;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Indent\Imgcache\Drivers\Contracts\DriverInterface;
use Indent\Imgcache\Imgcache\DownloadManager;
use Stringable;

/**
 * Docs: https://cloudinary.com/documentation/transformation_reference
 */
class Cloudinary extends BaseDriver implements DriverInterface, Stringable
{
    private string $source;
    private string|null $format = null;
    private Collection $parameters;

    public function __construct()
    {
        $this->parameters = Collection::make([]);
    }

    public function make(string $source): self
    {
        $this->validateInput(get_defined_vars(), [
            'source' => 'required|url',
        ]);

        $this->source = $source;

        return $this;
    }

    public function blur(int $blur): self
    {
        $this->validateInput(get_defined_vars(), [
            'blur' => 'required|integer|digits_between:1,2000',
        ]);

        $this->parameters->push('e_blur:' . $blur);

        return $this;
    }

    public function brightness(int $brightness): self
    {
        $this->validateInput(get_defined_vars(), [
            'brightness' => 'required|integer|min:-99|max:100',
        ]);

        $this->parameters->push('e_brightness:' . $brightness);

        return $this;
    }

    public function crop(int $width, int $height = null): self
    {
        $this->validateInput(get_defined_vars(), [
            'width' => 'required|integer',
            'height' => 'integer',
        ]);

        $this->parameters->push('c_crop');
        $this->parameters->push('w_' . $width);
        
        if (!is_null($height)) {
            $this->parameters->push('h_' . $height);
        }

        return $this;
    }

    public function format(string $format): self
    {
        $this->validateInput(get_defined_vars(), [
            'format' => 'required|in:jpg,png,gif,avif,webp',
        ]);
        
        $this->format = $format;
        $this->parameters->push('f_' . $format);

        return $this;
    }

    public function height(int $height): self
    {
        $this->validateInput(get_defined_vars(), [
            'height' => 'required|integer',
        ]);

        $this->parameters->push('c_scale');
        $this->parameters->push('h_' . $height);
        
        return $this;
    }

    public function pixelate(int $pixelate): self
    {
        $this->validateInput(get_defined_vars(), [
            'pixelate' => 'required|integer|digits_between:1,200',
        ]);

        $this->parameters->push('e_pixelate:' . $pixelate);

        return $this;
    }

    public function width(int $width): self
    {
        $this->validateInput(get_defined_vars(), [
            'width' => 'required|integer',
        ]);

        $this->parameters->push('c_scale');
        $this->parameters->push('w_' . $width);
        
        return $this;
    }

    public function quality(string $quality): self
    {
        $this->validateInput(get_defined_vars(), [
            'quality' => 'required|in:best,good,eco,low',
        ]);

        $this->parameters->push('q_auto:' . $quality);

        return $this;
    }

    public function get(): string
    {
        $filePath = $this->createOrRetrieveFromCache();
        $hashName = Str::afterLast($filePath, '/');
        
        return "/imgcache/{$hashName}";
    }

    public function base64(): string
    {
        $filePath = $this->createOrRetrieveFromCache();
        $contents = file_get_contents($filePath);
        $type = mime_content_type($filePath);

        return 'data:' . $type . ';base64,' . base64_encode($contents);
    }

    private function createOrRetrieveFromCache(): string
    {
        $cloudName = config('services.imgcache.cloudinary.cloud_name');

        if (blank($cloudName)) {
            throw new Exception('Invalid cloud name for Cloudinary driver');
        }

        $endpoint = 'https://res.cloudinary.com/' . $cloudName . '/image/fetch';

        if ($this->parameters->isNotEmpty()) {
            $url = $endpoint . '/' . $this->parameters->unique()->join(',') . '/' . $this->source;
        } else {
            $url = $endpoint . '/' . $this->source;
        }

        $hashName = $this->getHashName($this->source, $this->parameters->toArray(), $this->format);
        $filePath = storage_path('imgcache/' . $hashName);

        // Create imgcache directory if it does not exists
        if (!File::exists(storage_path('imgcache'))) {
            File::makeDirectory(storage_path('imgcache'));
        }

        // Create file in cache if it does not exists
        if (!File::exists($filePath)) {
            $this->createCachedFile($hashName, app(DownloadManager::class)->get($url));
        }

        return $filePath;
    }

    public function __toString(): string
    {
        return $this->get();
    }
}
