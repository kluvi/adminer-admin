<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldForeignKeySelect extends AbstractAdminPlugin
{
    function editInput($table, $field, $attrs, $value)
    {
        // prints next column if exists as name
        $fks = foreign_keys($_GET['edit']);
        foreach ($fks as $fk) {
            if ($fk['source'][0] == $field['field']) {
                $foreignFields = fields($fk['table']);
                $foreignField = NULL;
                $foreignNextField = NULL;
                foreach ($foreignFields as $fkf) {
                    if ($fkf['field'] == $fk['target'][0]) {
                        $foreignField = $fkf;
                        continue;
                    }

                    if ($foreignField !== NULL) {
                        $foreignNextField = $fkf;
                        break;
                    }
                }

                if ($foreignNextField === NULL) {
                    $query = "SELECT `{$foreignField['field']}`, `{$foreignField['field']}` AS asgasgasg FROM `{$fk['table']}` ORDER BY `{$foreignField['field']}`";
                } else {
                    $query = "SELECT `{$foreignField['field']}`, CONCAT(`{$foreignField['field']}`, ' - ', `{$foreignNextField['field']}`) FROM `{$fk['table']}` ORDER BY `{$foreignField['field']}`";
                }

                $options = get_key_vals($query);
                $selected = $value;
                return "<select$attrs>" . optionlist($options, (string)$selected, 1) . "</select>"; // 1 - use keys
            }
        }
    }
}