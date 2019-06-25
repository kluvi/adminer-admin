<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldReadonly extends AbstractAdminPlugin
{
    function processInput($field, $value, $function = "")
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'readonly') {
            return q($value);
        }
    }

    function editInput($table, $field, $attrs, $value)
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'readonly') {
            return "<input type=\"hidden\" {$attrs} value=\"{$value}\"/>{$value}";
        }
    }
}