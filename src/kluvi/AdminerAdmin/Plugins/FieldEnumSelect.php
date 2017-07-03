<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldEnumSelect
{
    function editInput($table, $field, $attrs, $value)
    {
        if ($field["type"] == "enum") {
            $options = [];
            $selected = $value;
            preg_match_all("~'((?:[^']|'')*)'~", $field["length"], $matches);
            foreach ($matches[1] as $i => $val) {
                $val = stripcslashes(str_replace("''", "'", $val));
                $options[$i + 1] = $val;
                if ($value === $val) {
                    $selected = $i + 1;
                }
            }
            return "<select$attrs>" . optionlist($options, (string)$selected, 1) . "</select>"; // 1 - use keys
        }
    }
}