<?php

namespace kluvi\AdminerAdmin\Plugins;


class DisableNew extends AbstractAdminPlugin
{
    function selectLinks($tableStatus, $set = "")
    {
        $settings = $this->parseComment($tableStatus['Comment']);
        if ($settings->allowNew === false) {
            return '';
        }
    }
}