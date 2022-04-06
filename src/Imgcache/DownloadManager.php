<?php

namespace Indent\Imgcache\Imgcache;

class DownloadManager
{
    public function get(string $url): string
    {
        return file_get_contents($url);
    }
}
