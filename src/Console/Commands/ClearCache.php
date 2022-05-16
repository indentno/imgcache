<?php

namespace Indent\Imgcache\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;
use Symfony\Component\Finder\SplFileInfo;

class ClearCache extends Command
{
    protected $signature = 'imgcache:clear';
    protected $description = 'Delete all cached images';

    public function handle()
    {
        collect(File::allFiles(storage_path('imgcache'), true))
            ->reject(fn (SplFileInfo $file) => $file->getRelativePathname() === '.gitignore')
            ->each(fn (SplFileInfo $file) => unlink($file->getRealPath()));

        $this->info('Imgcache cleared successfully.');
    }
}
