<?php

namespace Indent\Imgcache\Drivers;

use Exception;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;

abstract class BaseDriver
{
    private function getSourceFileExtension(string $source): string
    {
        $extension = Str::afterLast($source, '.');

        return match ($extension) {
            'jpg', 'jpeg', 'png', 'gif', 'bmp', 'avif', 'webp' => $extension,
            default => 'jpg',
        };
    }

    protected function validateInput($data, $rules)
    {
        $validator = Validator::make($data, $rules);

        if ($validator->fails()) {
            throw new Exception($validator->errors()->first());
        };
    }

    protected function getHashName(string $source, array $options, string $extension = null): string
    {
        $date = date('Ymd');
        $appKeyHash = sha1(config('app.key'));
        $hash = sha1($source . json_encode($options) . $appKeyHash);
        
        $ext = $extension ?? $this->getSourceFileExtension($source);

        return "{$date}-{$hash}.{$ext}";
    }

    protected function createCachedFile(string $hashName, string $contents): string
    {
        // Concrete path to the Imgcache files
        $imgcachePath = storage_path('imgcache');

        // Create directory if it does not exists
        if (!File::exists($imgcachePath)) {
            File::makeDirectory($imgcachePath);
        }

        // Create file in cache
        $filePath = $imgcachePath . '/' . $hashName;
        File::put($filePath, $contents);

        // Return path to file
        return $filePath;
    }
}
