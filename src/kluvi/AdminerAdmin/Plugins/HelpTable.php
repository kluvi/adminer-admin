<?php

namespace kluvi\AdminerAdmin\Plugins;


class HelpTable extends AbstractAdminPlugin
{
    function selectLinks($tableStatus, $set = "")
    {
        $help = connection()->query("SELECT * FROM `adminer_help` WHERE `table`=" . q($tableStatus['Name']));
        if (is_object($help)) {
            $help = $help->fetch_assoc()['text'];
            echo "<div>{$help}</div>";
        }
    }
}