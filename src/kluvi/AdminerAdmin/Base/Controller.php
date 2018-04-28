<?php
namespace kluvi\AdminerAdmin\Base;

use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    public function index()
    {
        if (config('database.default') !== 'mysql') {
            throw new \InvalidArgumentException('Database driver must be "mysql');
        }
        $adminerPath = config('adminer-admin.adminer_download_dir') . '/adminer.php';
        $pluginsConfig = config('adminer-admin.plugins_config');
        $dbName = config('database.connections.mysql.database');
        $dbHost = config('database.connections.mysql.host');
        $dbUser = config('database.connections.mysql.username');
        $dbPassword = config('database.connections.mysql.password');

        AdminerFactory::run($adminerPath, $dbName, $dbHost, $dbUser, $dbPassword, $pluginsConfig);
    }
}