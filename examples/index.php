<?php
error_reporting(E_ALL);
ini_set('display_errors', 'on');

require "../vendor/autoload.php";

$downloadUrl = 'http://www.adminer.org/latest-editor.php';
$targetDir = './adminer-download';
$imagesBaseDir = './images/';
$imagesBaseUrl = 'http://localhost/adminer-admin-test/images/';
$db_database = 'test-adminer-admin';
$db_host = 'localhost';
$db_username = 'root';
$db_password = '';

$pluginsConfig = [
    'field_image' => [
        'baseDir' => $imagesBaseDir,
        'baseUrl' => $imagesBaseUrl,
    ],
];


// Uncomment this to download a copy of Adminer Editor from URL above an apply some patches to downloaded file
//$downloader = new \kluvi\AdminerAdmin\Base\Downloader;
//$downloader->download($downloadUrl, $targetDir);

\kluvi\AdminerAdmin\Base\AdminerFactory::run($targetDir . '/adminer.php', $db_database, $db_host, $db_username, $db_password, $pluginsConfig);
