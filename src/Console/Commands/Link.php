<?php

namespace Indent\Imgcache\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class Link extends Command
{
    protected $signature = 'imgcache:link';
    protected $description = 'Create the symbolic links for Imgcache';

    public function handle()
    {
        $this->symlink();
    }
    
    private function symlink()
    {
        $target = storage_path('imgcache');
        $link = public_path('imgcache');
        
        // Create imgcache if it does not exists
        if (!File::exists($target)) {
            File::makeDirectory($target);
        }

        // Create .gitignore if it does not exists
        $gitignorePath = storage_path('imgcache/.gitignore');

        if (!File::exists($gitignorePath)) {
            File::put($gitignorePath, "*\n!.gitignore\n");
        }
        
        if (File::exists($link)) {
            $this->warn('Found existing symlink. Performing overwrite.');
            unlink($link);
        }
        
        $this->info('Imgcache symlinked successfully to public directory.');
        File::relativeLink($target, $link);
    }
}
