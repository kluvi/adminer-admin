<?php

namespace kluvi\AdminerAdmin\Base;

use kluvi\AdminerAdmin\Base\Traits\CommentParse;
use kluvi\AdminerAdmin\Plugins\AbstractAdminPlugin;

class AdminerAdmin extends \Adminer
{
    use CommentParse;

    public $plugins = [];
    protected $credentials = ['localhost', 'root', ''];
    protected $database;

    /** Register plugins
     *
     * @param  $plugins array associative array of object instances
     */
    function __construct($database, array $plugins = [])
    {
        $this->database = $database;
        foreach ($plugins as $plugin) {
            $config = [];
            if (is_array($plugin)) {
                $config = $plugin[1];
                $plugin = $plugin[0];
            }

            if (is_string($plugin)) {
                $plugin = new $plugin;
            }

            if ($plugin instanceof AbstractAdminPlugin) {
                $plugin->setAdmin($this);
                $plugin->setConfig($config);
            }

            $key = get_class($plugin);

            $this->plugins[$key] = $plugin;
        }
    }

    function name()
    {
        return 'Adminer Admin';
    }

    public function setCredentials($host, $login, $password)
    {
        return $this->credentials = [$host, $login, $password];
    }

    function credentials()
    {
        return $this->credentials;
    }

    function database()
    {
        return $this->database;
    }

    function login($login, $password)
    {
        connect();
        connection()->query("USE `{$this->database()}`");

        $user = connection()->query("SELECT * FROM `adminer_users` WHERE `login`=" . q($login));
        if ($user !== false) {
            $user = $user->fetch_assoc();
        }

        if (!is_array($user)) {
            return false;
        }

        return password_verify($password, $user['password']);
    }


    // taken from https://raw.githubusercontent.com/vrana/adminer/master/plugins/plugin.php

    function _callParent($function, $args)
    {
        return call_user_func_array(array('parent', $function), $args);
    }

    function _applyPlugin($function, $args)
    {
        foreach ($this->plugins as $plugin) {
            if (method_exists($plugin, $function)) {
                switch (count($args)) { // call_user_func_array() doesn't work well with references
                    case 0:
                        $return = $plugin->$function();
                        break;
                    case 1:
                        $return = $plugin->$function($args[0]);
                        break;
                    case 2:
                        $return = $plugin->$function($args[0], $args[1]);
                        break;
                    case 3:
                        $return = $plugin->$function($args[0], $args[1], $args[2]);
                        break;
                    case 4:
                        $return = $plugin->$function($args[0], $args[1], $args[2], $args[3]);
                        break;
                    case 5:
                        $return = $plugin->$function($args[0], $args[1], $args[2], $args[3], $args[4]);
                        break;
                    case 6:
                        $return = $plugin->$function($args[0], $args[1], $args[2], $args[3], $args[4], $args[5]);
                        break;
                    default:
                        trigger_error('Too many parameters.', E_USER_WARNING);
                }
                if ($return !== null) {
                    return $return;
                }
            }
        }
        return $this->_callParent($function, $args);
    }

    function _appendPlugin($function, $args)
    {
        $return = $this->_callParent($function, $args);
        foreach ($this->plugins as $plugin) {
            if (method_exists($plugin, $function)) {
                $return += call_user_func_array(array($plugin, $function), $args);
            }
        }
        return $return;
    }

    // appendPlugin

    function dumpFormat()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function dumpOutput()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function editFunctions($field)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    // applyPlugin

    function permanentLogin($create = false)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function schemas()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function databases($flush = true)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function queryTimeout()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function headers()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function head()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function loginForm()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function tableName($tableStatus)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function fieldName($field, $order = 0)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectLinks($tableStatus, $set = "")
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function foreignKeys($table)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function backwardKeys($table, $tableName)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function backwardKeysPrint($backwardKeys, $row)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectQuery($query, $time)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function sqlCommandQuery($query)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function rowDescription($table)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function rowDescriptions($rows, $foreignKeys)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectLink($val, $field)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectVal($val, $link, $field, $original)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function editVal($val, $field)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function tableStructurePrint($fields)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function tableIndexesPrint($indexes)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectColumnsPrint($select, $columns)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectSearchPrint($where, $columns, $indexes)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectOrderPrint($order, $columns, $indexes)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectLimitPrint($limit)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectLengthPrint($text_length)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectActionPrint($indexes)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectCommandPrint()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectImportPrint()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectEmailPrint($emailFields, $columns)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectColumnsProcess($columns, $indexes)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectSearchProcess($fields, $indexes)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectOrderProcess($fields, $indexes)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectLimitProcess()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectLengthProcess()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectEmailProcess($where, $foreignKeys)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function selectQueryBuild($select, $where, $group, $order, $limit, $page)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function messageQuery($query, $time)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function editInput($table, $field, $attrs, $value)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function processInput($field, $value, $function = "")
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function dumpDatabase($db)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function dumpTable()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function dumpData($table, $style, $query)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function dumpFilename($identifier)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function dumpHeaders($identifier, $multi_table = false)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function homepage()
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function navigation($missing)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function databasesPrint($missing)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }

    function tablesPrint($tables)
    {
        $args = func_get_args();
        return $this->_applyPlugin(__FUNCTION__, $args);
    }
}