<?php

namespace Indent\Imgcache\Imgcache;

use Exception;
use Illuminate\Support\Facades\Log;

class DownloadManager
{
    public function get(string $processedUrl): string
    {
        try {
            return file_get_contents($processedUrl);
        } catch (Exception $e) {
            Log::error($e);

            return file_get_contents(__DIR__ . '/../../resources/img/error.png');
        }
    }
}
