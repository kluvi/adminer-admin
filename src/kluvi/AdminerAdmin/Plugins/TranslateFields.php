<?php

namespace kluvi\AdminerAdmin\Plugins;


class TranslateFields extends AbstractAdminPlugin
{
    function fieldName($field, $order = 0)
    {
        $settings = $this->parseComment($field['comment']);
        if (!$settings->name) {
            return '';
        }

        return h($settings->name);
    }
}