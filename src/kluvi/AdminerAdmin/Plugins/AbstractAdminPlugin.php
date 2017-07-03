<?php

namespace kluvi\AdminerAdmin\Plugins;

use kluvi\AdminerAdmin\Base\AdminerAdmin;
use kluvi\AdminerAdmin\Base\Traits\CommentParse;

abstract class AbstractAdminPlugin
{
    use CommentParse;

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
}