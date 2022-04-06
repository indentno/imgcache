<?php

namespace Indent\Imgcache\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class ClearCache extends Command
{
    protected $signature = 'imgcache:clear';
    protected $description = 'Delete all cached images';

    public function handle()
    {
        File::cleanDirectory(storage_path('imgcache'));

        $this->info('Imgcache cleared successfully.');
    }
}
