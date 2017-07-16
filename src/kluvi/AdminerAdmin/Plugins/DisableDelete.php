<?php

namespace kluvi\AdminerAdmin\Plugins;


class DisableDelete extends AbstractAdminPlugin
{
    function head()
    {
        if (isset($_GET['edit'])) {
            $tableStatus = table_status1($_GET['edit']);
            if (isset($tableStatus['Comment'])) {
                $settings = $this->parseComment($tableStatus['Comment']);
                if ($settings->allowDelete === false) {
                    echo '<style>input[name=delete] { display: none; }</style>';
                }
            }
        }
    }
}