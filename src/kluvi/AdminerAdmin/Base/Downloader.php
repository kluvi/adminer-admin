<?php

namespace kluvi\AdminerAdmin\Base;


class Downloader
{
    public function download($url, $targetDir)
    {
        if (!file_exists($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $target = $targetDir . '/adminer.php';

        $content = file_get_contents($url);
        $content = $this->fixAdminer($content);
        file_put_contents($target, $content);

        return $target;
    }

    public function fixAdminer($content)
    {
        $content = preg_replace("/\\bredirect\\(/im", 'adminer_redirect(', $content);
        $content = preg_replace("/\\bcookie\\(/im", 'adminer_cookie(', $content);
        $content = preg_replace("/\\bview\\(/im", 'adminer_view(', $content);
        $content = preg_replace("/adminer_object/im", 'adminer_admin_object', $content);

        return $content;
    }
}