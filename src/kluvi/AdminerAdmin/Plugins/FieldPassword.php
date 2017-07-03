<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldPassword extends AbstractAdminPlugin
{
    function fieldName($field, $order = 0)
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'password' && !isset($_GET['edit'])) {
            return '';
        }
    }

    function editInput($table, $field, $attrs, $value)
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'password') {
            return '<input type="password" ' . $attrs . ' />';
        }
    }

    function processInput($field, $value, $function = "")
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'password') {
            return q(password_hash($value, PASSWORD_BCRYPT, [
                'cost' => 11,
            ]));
        }
    }
}