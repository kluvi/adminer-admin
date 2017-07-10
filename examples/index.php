<?php
require "./vendor/autoload.php";

$downloadUrl = 'https://github.com/vrana/adminer/releases/download/v4.3.1/editor-4.3.1-mysql.php';
$targetDir = './adminer-download';


// Uncomment this to download a copy of Adminer Editor from URL above an apply some patches to downloaded file
//$downloader = new \kluvi\AdminerAdmin\Base\Downloader;
//$downloader->download($downloadUrl, $targetDir);


$pluginsConfig = [
    'field_image' => [
        'baseDir' => './images/',
        'baseUrl' => 'http://localhost/adminer-admin-test/images/',
    ],
];
\kluvi\AdminerAdmin\Base\AdminerFactory::run($targetDir . '/adminer.php', $database = 'test-adminer-admin', $host = 'localhost', $username = 'root', $password = '', $pluginsConfig);
