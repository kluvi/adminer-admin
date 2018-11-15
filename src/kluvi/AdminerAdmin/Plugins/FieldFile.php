<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldFile extends AbstractAdminPlugin
{
    function selectVal($val, $link, $field, $original)
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'file' || $settings->type === 'image') {
            if (is_file($this->config['baseDir'] . '/' . $val)) {
                if ($settings->type === 'image') {
                    $size = getimagesize($this->config['baseDir'] . '/' . $val);
                    $filesize = $this->formatBytes(filesize($this->config['baseDir'] . '/' . $val), 0);
                    return '<img src="' . $this->config['baseUrl'] . '/' . $val . '" /><br />' . $size[0] . 'x' . $size[1] . 'px, ' . $filesize;
                } else {
                    $filesize = $this->formatBytes(filesize($this->config['baseDir'] . '/' . $val), 0);
                    return '<a href="' . $this->config['baseUrl'] . '/' . $val . '" target="_blank">' . $val . '</a><br />' . $filesize;
                }
            }
            return ' ';
        }
    }

    function processInput($field, $value, $function = "")
    {
        $settings = $this->parseComment($field['comment']);
        if (($settings->type == 'file' || $settings->type === 'image') && isset($_GET['where'])) {
            $id = reset($_GET['where']);
            $tableName = $_GET['edit'];
            $fieldName = $field['field'];
            $dir = $tableName . '/' . $fieldName . '/' . $id;

            if (isset($_POST['fields'][$fieldName . '__remove_file']) && $_POST['fields'][$fieldName . '__remove_file'] == 'yes') {
                $content = glob($this->config['baseDir'] . '/' . $dir . '/*');
                foreach ($content as $file) {
                    unlink($file);
                }
                rmdir($this->config['baseDir'] . '/' . $dir);
                return q('');
            }

            if (!file_exists($this->config['baseDir'])) {
                mkdir($this->config['baseDir'], 0777);
            }

            if (!file_exists($this->config['baseDir'] . '/' . $tableName)) {
                mkdir($this->config['baseDir'] . '/' . $tableName, 0777);
            }

            if (!file_exists($this->config['baseDir'] . '/' . $tableName . '/' . $fieldName)) {
                mkdir($this->config['baseDir'] . '/' . $tableName . '/' . $fieldName, 0777);
            }

            if (!file_exists($this->config['baseDir'] . '/' . $tableName . '/' . $fieldName . '/' . $id)) {
                mkdir($this->config['baseDir'] . '/' . $tableName . '/' . $fieldName . '/' . $id, 0777);
            }

            $files = $_FILES['fields'];

            if ($files["error"][$fieldName]) {
                return false;
            }

            $content = glob($this->config['baseDir'] . '/' . $dir . '/*');
            foreach ($content as $file) {
                unlink($file);
            }

            $filename = $files['name'][$fieldName];
            if (!move_uploaded_file($files["tmp_name"][$fieldName], $this->config['baseDir'] . '/' . $dir . '/' . $filename)) {
                return false;
            }
            return q($dir . '/' . $filename);
        }

        return q($value);
    }

    function editInput($table, $field, $attrs, $value)
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'file' || $settings->type === 'image') {
            if (!isset($_GET['where'])) {
                return 'Files can be uploaded after saving record.';
            }
            $remove = '<label><input type="checkbox" name="fields[' . $field['field'] . '__remove_file]" value="yes"/>Remove file</label>';
            $input = '<input type="file" ' . $attrs . ' />';
            $preview = '';

            if (is_file($this->config['baseDir'] . '/' . $value)) {
                if ($settings->type === 'image') {
                    $size = getimagesize($this->config['baseDir'] . '/' . $value);
                    $filesize = $this->formatBytes(filesize($this->config['baseDir'] . '/' . $value), 0);
                    $preview = '<br /><br /><img src="' . $this->config['baseUrl'] . '/' . $value . '" />';
                    $preview .= '<br />' . $size[0] . 'x' . $size[1] . 'px, ' . $filesize;
                } else {
                    $filesize = $this->formatBytes(filesize($this->config['baseDir'] . '/' . $value), 0);
                    $preview = '<br /><br /><a href="' . $this->config['baseUrl'] . '/' . $value . '" target="_blank">' . $value . '</a>';
                    $preview .= '<br />' . $filesize;
                }
            }

            return $input . $remove . $preview;
        }
    }

    function formatBytes($bytes, $precision = 2)
    {
        $units = array('B', 'kB', 'MB', 'GB', 'TB');

        $bytes = max($bytes, 0);
        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
        $pow = min($pow, count($units) - 1);
        $bytes /= pow(1024, $pow);

        return round($bytes, $precision) . '' . $units[$pow];
    }
}