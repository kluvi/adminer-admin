<?php

namespace kluvi\AdminerAdmin\Plugins;

use kluvi\AdminerAdmin\Base\AdminerAdmin;
use kluvi\AdminerAdmin\Base\Traits\CommentParse;

abstract class AbstractAdminPlugin
{
    use CommentParse;

    /** @var AdminerAdmin */
    protected $admin;
    protected $config;

    public function setAdmin(AdminerAdmin $admin)
    {
        $this->admin = $admin;
    }

    public function setConfig($config)
    {
        $this->config = $config;
    }

    public function getFromType($settings, $table)
    {
        if (!isset($settings->type_from)) {
            return;
        }

        if (!isset($_GET['where'])) {
            return;
        }

        connect();
        connection()->query("USE `{$this->admin->database()}`");
        $where = [];
        foreach ($_GET['where'] as $k => $v) {
            $where[] = "`{$k}` = '{$v}'";
        }
        $where = implode(' AND ', $where);
        $value = connection()->query("SELECT `{$settings->type_from}` FROM `{$table}` WHERE {$where}");
        if (!$value) {
            return;
        }
        $value = $value->fetch_assoc()[$settings->type_from];

        return $value;
    }
}