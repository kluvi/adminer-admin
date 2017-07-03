<?php

namespace kluvi\AdminerAdmin\Commands;

use Illuminate\Console\Command;
use kluvi\AdminerAdmin\Base\Downloader;

class DownloadCommand extends Command
{
    protected $signature = 'adminer-admin:download';
    protected $description = 'Download and patch adminer';

    public function handle(Downloader $downloader)
    {
        $url = config('adminer-admin.adminer_download_url');
        $targetDir = config('adminer-admin.adminer_download_dir');
        $downloader->download($url, $targetDir);
    }
}
