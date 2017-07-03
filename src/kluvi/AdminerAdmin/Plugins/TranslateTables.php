<?php

namespace kluvi\AdminerAdmin\Plugins;


class TranslateTables extends AbstractAdminPlugin
{
    function tableName($tableStatus)
    {
        $settings = $this->parseComment($tableStatus['Comment']);
        if (!$settings->name) {
            return '';
        }

        return h($settings->name);
    }
}