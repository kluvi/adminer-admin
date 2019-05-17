<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldDate extends AbstractAdminPlugin
{
    protected $scriptsPrinted = false;

    function editInput($table, $field, $attrs, $value)
    {
        $settings = $this->parseComment($field['comment']);

        if ($settings->type == 'date') {
            if ($value === '0.0.0000') {
                $value = '';
            }
            return '<input name="fields[' . $field['field'] . ']" value="' . $value . '" />&nbsp;(d.m.rrrr)';
        }
    }

    function processInput($field, $value, $function = "")
    {
        $settings = $this->parseComment($field['comment']);

        if ($settings->type == 'date') {
            $value = str_replace(' ', '', $value);
            $date = date_create_from_format('j.n.Y', $value);
            if (!$date instanceof \DateTime) {
                $date = date_create_from_format('d.m.Y', $value);
            }

            if (!$date instanceof \DateTime) {
                return 'NULL';
            }

            return q($date->format('Y-m-d'));
        }
    }

    function selectVal($val, $link, $field, $original)
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'date') {
            if ($original === '0000-00-00') {
                return '';
            }
        }
    }
}