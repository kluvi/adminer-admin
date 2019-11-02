<?php

namespace kluvi\AdminerAdmin\Base;


use kluvi\AdminerAdmin\Base\Exceptions\AdminException;
use kluvi\AdminerAdmin\Plugins\DisableDelete;
use kluvi\AdminerAdmin\Plugins\DisableNew;
use kluvi\AdminerAdmin\Plugins\FieldColor;
use kluvi\AdminerAdmin\Plugins\FieldDate;
use kluvi\AdminerAdmin\Plugins\FieldRichEditor;
use kluvi\AdminerAdmin\Plugins\FieldEnumSelect;
use kluvi\AdminerAdmin\Plugins\FieldFile;
use kluvi\AdminerAdmin\Plugins\FieldForeignKeySelect;
use kluvi\AdminerAdmin\Plugins\FieldPassword;
use kluvi\AdminerAdmin\Plugins\FieldReadonly;
use kluvi\AdminerAdmin\Plugins\HelpTable;
use kluvi\AdminerAdmin\Plugins\HideFunctions;
use kluvi\AdminerAdmin\Plugins\FieldMultiInput;
use kluvi\AdminerAdmin\Plugins\TranslateFields;
use kluvi\AdminerAdmin\Plugins\TranslateTables;

class AdminerFactory
{
    protected static $config = [];

    public static function create()
    {
        $plugins = [
            // fieds must be registered first, because TranslateFields overrides fieldName() and field then cant disable self
            FieldColor::class,
            FieldDate::class,
            FieldMultiInput::class,
            [FieldRichEditor::class, self::$config['plugins']['field_rich_editor']],
            FieldPassword::class,
            [FieldFile::class, self::$config['plugins']['field_image']],
            FieldReadonly::class,
            FieldForeignKeySelect::class,
            FieldEnumSelect::class,

            HideFunctions::class,
            DisableDelete::class,
            DisableNew::class,
            HelpTable::class,
            TranslateTables::class,
            TranslateFields::class,
        ];
        $adminerAdmin = new AdminerAdmin(self::$config['database'], $plugins);
        $adminerAdmin->setCredentials(self::$config['host'], self::$config['username'], self::$config['password']);
        return $adminerAdmin;
    }

    /**
     * Config and require Adminer Admin
     *
     * @param $adminerPath
     * @param $database string Name of the database
     * @param $host string hostname of the database
     * @param $username string username for the database
     * @param $password string password for the database
     * @param $pluginsConfig array ['field_image' => 'baseDir' => '', 'baseUrl' => '']
     * @throws AdminException
     */
    public static function run($adminerPath, $database, $host, $username, $password, $pluginsConfig)
    {
        self::$config = [
            'database' => $database,
            'host' => $host,
            'username' => $username,
            'password' => $password,
            'plugins' => $pluginsConfig,
        ];
        $adminerPath = realpath($adminerPath);
        if (!file_exists($adminerPath)) {
            throw new AdminException('Adminer is not downloaded, please run `php artisan adminer-admin:download`');
        }
        require_once __DIR__ . '/instantiator.php';
        require_once $adminerPath;
    }
}