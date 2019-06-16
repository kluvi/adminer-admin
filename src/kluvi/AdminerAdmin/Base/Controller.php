<?php
namespace kluvi\AdminerAdmin\Base;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller as BaseController;
use Symfony\Component\HttpFoundation\File\UploadedFile;

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

    public function upload(Request $request)
    {
        $baseDir = config('adminer-admin.plugins_config.baseDir', public_path('files'));
        $files = $request->files->all();
        foreach($files as $key => $file) {
            if($file instanceof UploadedFile) {
                $fileName = uniqid().'-'.$file->getClientOriginalName();
                $file->move($baseDir, $fileName);
                return response()->json([
                    'success' => true,
                    'file' => asset(config('adminer-admin.plugins_config.dir', 'files').'/'.$fileName),
                ]);
            }
        }

        return response()->json([
            'success' => false,
        ]);
    }
}