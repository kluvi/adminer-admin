<?php
function adminer_admin_object(...$args)
{
    return call_user_func_array([\kluvi\AdminerAdmin\Base\AdminerFactory::class, 'create'], $args);
}