<?php

namespace kluvi\AdminerAdmin\Base\Traits;


trait CommentParse
{
    function parseComment($comment)
    {
        $settings = json_decode($comment, true);
        return (object)$settings;
    }
}