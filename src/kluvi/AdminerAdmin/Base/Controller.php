<?php
namespace kluvi\AdminerAdmin\Base;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        $adminerPath = config('adminer-admin.adminer_download_dir') . '/adminer.php';
        $pluginsConfig = config('adminer-admin.plugins_config');

        AdminerFactory::require ($adminerPath, env('DB_DATABASE'), env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'), $pluginsConfig);
    }
}