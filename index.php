<?php
//ini_set('display_errors', 'on');

function adminer_object()
{

    class AdminerAdmin extends Adminer
    {
        public $baseDir;
        public $baseUrl;
        public $database;
        public $credentials;

        function parseComment($comment)
        {
            $settings = json_decode($comment, true);
            if (!isset($settings['show'])) {
                $settings['show'] = true;
            }
            return (object)$settings;
        }

        function name()
        {
            return 'Adminer Admin';
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
            if ($login == 'kluvi' && password_verify($password, '$2y$11$.DrsFmdUHlUycMGqc4zLs.h/WSmYvy6wYVkfUdTmAMac8fRsI58pK')) {
                return true;
            }

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

        /** Returns export output options
         * @return array
         */
        function dumpOutput()
        {
            return [];
        }

        /** Returns export format options
         * @return array empty to disable export
         */
        function dumpFormat()
        {
            return [];
        }

        /** Print import box in select
         * @return bool whether to print default import
         */
        function selectImportPrint()
        {
            return false;
        }

        /** Print HTML code inside <head>
         * @return bool true to link adminer.css if exists
         */
        function head()
        {
            ?>
            <script type="text/javascript" src="https://code.jquery.com/jquery-3.2.1.min.js"></script>
            <script type="text/javascript">
                /* HTML5 Sortable (http://farhadi.ir/projects/html5sortable) */
                (function (a) {
                    var b, c = a();
                    a.fn.sortable = function (d) {
                        var e = String(d);
                        return d = a.extend({connectWith: !1}, d), this.each(function () {
                            if (/^enable|disable|destroy$/.test(e)) {
                                var f = a(this).children(a(this).data("items")).attr("draggable", e == "enable");
                                e == "destroy" && f.add(this).removeData("connectWith items").off("dragstart.h5s dragend.h5s selectstart.h5s dragover.h5s dragenter.h5s drop.h5s");
                                return
                            }
                            var g, h, f = a(this).children(d.items),
                                i = a("<" + (/^ul|ol$/i.test(this.tagName) ? "li" : "div") + ' class="sortable-placeholder">');
                            f.find(d.handle).mousedown(function () {
                                g = !0
                            }).mouseup(function () {
                                g = !1
                            }), a(this).data("items", d.items), c = c.add(i), d.connectWith && a(d.connectWith).add(this).data("connectWith", d.connectWith), f.attr("draggable", "true").on("dragstart.h5s", function (c) {
                                if (d.handle && !g) return !1;
                                g = !1;
                                var e = c.originalEvent.dataTransfer;
                                e.effectAllowed = "move", e.setData("Text", "dummy"), h = (b = a(this)).addClass("sortable-dragging").index()
                            }).on("dragend.h5s", function () {
                                b.removeClass("sortable-dragging").show(), c.detach(), h != b.index() && f.parent().trigger("sortupdate", {item: b}), b = null
                            }).not("a[href], img").on("selectstart.h5s", function () {
                                return this.dragDrop && this.dragDrop(), !1
                            }).end().add([this, i]).on("dragover.h5s dragenter.h5s drop.h5s", function (e) {
                                return !f.is(b) && d.connectWith !== a(b).parent().data("connectWith") ? !0 : e.type == "drop" ? (e.stopPropagation(), c.filter(":visible").after(b), !1) : (e.preventDefault(), e.originalEvent.dataTransfer.dropEffect = "move", f.is(this) ? (d.forcePlaceholderSize && i.height(b.outerHeight()), b.hide(), a(this)[i.index() < a(this).index() ? "after" : "before"](i), c.not(i).detach()) : !c.is(this) && !a(this).children(d.items).length && (c.detach(), a(this).append(i)), !1)
                            })
                        })
                    }
                })(jQuery);

                $(document).ready(function () {
                    $(document).on('click', '.inputs .input button', function () {
                        $(this).closest('.input').remove();
                        return false;
                    });

                    $(document).on('click', 'button.addMultiInput', function () {
                        $(this).prev().append('<li class="input"><span class="handle">&#9776;</span><input name="fields[labels][]"><button>odstranit</button></li>');
                        $('.inputs').sortable('destroy').sortable({
                            handle: '.handle'
                        });
                        return false;
                    });

                    $('.inputs').sortable({
                        handle: '.handle'
                    });
                });
            </script>
            <style>
                .inputs {
                    padding: 0;
                }

                .inputs li.input {
                    list-style: none;
                }

                #table thead tr:first-child td:first-child a,
                #table [type=checkbox],
                p.count label {
                    display: none;
                }
            </style>
            <?php

            if(isset($_GET['edit'])) {
                $tableStatus = table_status1($_GET['edit']);
                if(isset($tableStatus['Comment'])) {
                    $settings = $this->parseComment($tableStatus['Comment']);
                    if($settings->allowDelete === false) {
                        echo '<style>input[name=delete] { display: none; }</style>';
                    }
                }
            }
            return true;
        }

        /** Table caption used in navigation and headings
         * @param array result of SHOW TABLE STATUS
         * @return string HTML code, "" to ignore table
         */
        function tableName($tableStatus)
        {
            $settings = $this->parseComment($tableStatus['Comment']);
            if (!$settings->show || !$settings->name) {
                return '';
            }

            return h($settings->name);
        }

        /** Field caption used in select and edit
         * @param array single field returned from fields()
         * @param int order of column in select
         * @return string HTML code, "" to ignore field
         */
        function fieldName($field, $order = 0)
        {
            $settings = $this->parseComment($field['comment']);
            if (!$settings->show || !$settings->name) {
                return '';
            }

            // hide password from table
            if ($settings->type == 'password' && !isset($_GET['edit'])) {
                return '';
            }

            return h($settings->name);
        }

        /** Print links after select heading
         * @param array result of SHOW TABLE STATUS
         * @param string new item options, NULL for no new item
         * @return null
         */
        function selectLinks($tableStatus, $set = "")
        {
            $help = connection()->query("SELECT * FROM `adminer_help` WHERE `table`=" . q($tableStatus['Name']));
            if (is_object($help)) {
                $help = $help->fetch_assoc()['text'];
                echo "<div>{$help}</div>";
            }
            $settings = $this->parseComment($tableStatus['Comment']);
            if ($settings->allowNew === false) {
                return;
            }

            echo '<p class="links">';
            if ($set !== null) {
                $links["edit"] = lang('New item');
            }
            foreach ($links as $key => $val) {
                echo " <a href='" . h(ME) . "$key=" . urlencode($tableStatus["Name"]) . ($key == "edit" ? $set : "") . "'" . bold(isset($_GET[$key])) . ">$val</a>";
            }
            echo "\n";
        }

        /** Print search box in select
         * @param array result of selectSearchProcess()
         * @param array selectable columns
         * @param array
         * @return null
         */
        function selectSearchPrint($where, $columns, $indexes)
        {

        }

        /** Print order box in select
         * @param array result of selectOrderProcess()
         * @param array selectable columns
         * @param array
         * @return null
         */
        function selectOrderPrint($order, $columns, $indexes)
        {

        }

        /** Print limit box in select
         * @param string result of selectLimitProcess()
         * @return null
         */
        function selectLimitPrint($limit)
        {

        }

        /** Print action box in select
         * @param array
         * @return null
         */
        function selectActionPrint($indexes)
        {

        }


        /** Functions displayed in edit form
         * @param array single field from fields()
         * @return array
         */
        function editFunctions($field)
        {
            return [];
        }

        /** Print command box in select
         * @return bool whether to print default commands
         */
        function selectCommandPrint()
        {
            return false;
        }

        /** Get options to display edit field
         * @param string table name
         * @param array single field from fields()
         * @param string attributes to use inside the tag
         * @param string
         * @return string custom input field or empty string for default
         */
        function editInput($table, $field, $attrs, $value)
        {
            $settings = $this->parseComment($field['comment']);

            if ($settings->type == 'readonly') {
                return (strlen($value) == 0 ? ' ' : $value);
            }

            if ($settings->type == 'file' || $settings->type == 'image') {
                if (!isset($_GET['where'])) {
                    return 'Soubory lze nahrávat až po uložení záznamu.';
                }
                $remove = '<label><input type="checkbox" name="fields[' . $field['field'] . '__remove_file]" value="yes"/>Odstranit soubor</label>';
                $input = '<input type="file" ' . $attrs . ' />';
                $preview = '';
                if ($settings->type == 'image') {
                    if (file_exists($this->baseDir . '/' . $value)) {
                        $size = getimagesize($this->baseDir . '/' . $value);
                        $filesize = $this->formatBytes(filesize($this->baseDir . '/' . $value), 0);
                        $preview = '<br /><br /><img src="' . $this->baseUrl . '/' . $value . '" />';
                        $preview .= '<br />' . $size[0] . 'x' . $size[1] . 'px, '.$filesize;
                    }
                }
                return $input . $remove . $preview;
            }

            if ($settings->type == 'multi-input') {
                $return = '';
                $return .= '<ul class="inputs">';
                $values = [];
                $decoded = @json_decode($value, true);
                if (is_array($decoded)) {
                    foreach ($decoded as $input) {
                        $return .= '<li class="input">';
                        $return .= '<span class="handle">&#9776;</span>';
                        $return .= '<input name="fields[' . $field['field'] . '][]" value="' . $input . '" />';
                        $return .= '<button>odstranit</button>';
                        $return .= '</li>';
                    }
                }
                $return .= '<li class="input">';
                $return .= '<span class="handle">&#9776;</span>';
                $return .= '<input name="fields[' . $field['field'] . '][]" />';
                $return .= '<button>odstranit</button>';
                $return .= '</li>';
                $return .= '</ul>';
                $return .= '<button class="addMultiInput">přidat</button>';
                return $return;
            }

            if ($settings->type == 'password') {
                return '<input type="password" ' . $attrs . ' />';
            }

            if ($field["type"] == "enum") {
                $options = array("" => array());
                $selected = $value;
                if (isset($_GET["select"])) {
                    $options[""][-1] = lang('original');
                }
                if ($field["null"]) {
                    $options[""][""] = "NULL";
                    if ($value === null && !isset($_GET["select"])) {
                        $selected = "";
                    }
                }
                $options[""][0] = lang('empty');
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

            // print foreign keys as selects
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
            return "";
        }

        /** Process sent input
         * @param array single field from fields()
         * @param string
         * @param string
         * @return string expression to use in a query
         */
        function processInput($field, $value, $function = "")
        {
            if ($function == "SQL") {
                return $value; // SQL injection
            }

            $settings = $this->parseComment($field['comment']);
            if ($settings->type == 'readonly') {
                return $value;
            }

            if ($settings->type == 'password') {
                return q(password_hash($value, PASSWORD_BCRYPT, [
                    'cost' => 11,
                ]));
            }

            if ($settings->type == 'file' || $settings->type == 'image') {
                $id = reset($_GET['where']);
                $tableName = $_GET['edit'];
                $fieldName = $field['field'];
                $dir = $tableName . '/' . $fieldName . '/' . $id;

                if (isset($_POST['fields'][$fieldName . '__remove_file']) && $_POST['fields'][$fieldName . '__remove_file'] == 'yes') {
                    $content = glob($this->baseDir . '/' . $dir . '/*');
                    foreach ($content as $file) {
                        unlink($file);
                    }
                    rmdir($this->baseDir . '/' . $dir);
                    return q('');
                }

                if (!file_exists($this->baseDir . '/' . $tableName)) {
                    mkdir($this->baseDir . '/' . $tableName, 0777);
                }

                if (!file_exists($this->baseDir . '/' . $tableName . '/' . $fieldName)) {
                    mkdir($this->baseDir . '/' . $tableName . '/' . $fieldName, 0777);
                }

                if (!file_exists($this->baseDir . '/' . $tableName . '/' . $fieldName . '/' . $id)) {
                    mkdir($this->baseDir . '/' . $tableName . '/' . $fieldName . '/' . $id, 0777);
                }

                $files = $_FILES['fields'];

                if ($files["error"][$fieldName]) {
                    return false;
                }

                $content = glob($this->baseDir . '/' . $dir . '/*');
                foreach ($content as $file) {
                    unlink($file);
                }

                $filename = $files['name'][$fieldName];
                if (!move_uploaded_file($files["tmp_name"][$fieldName], $this->baseDir . '/' . $dir . '/' . $filename)) {
                    return false;
                }
                return q($dir . '/' . $filename);
            }

            if ($settings->type == 'multi-input') {
                if (!is_array($value)) {
                    return q('[]');
                }
                foreach ($value as $k => $v) {
                    $v = trim($v);
                    if (strlen($v) == 0) {
                        unset($value[$k]);
                    } else {
                        $value[$k] = $v;
                    }
                }
                return q(json_encode(array_values($value)));
            }

            return q($value);
        }

        /** Value printed in select table
         * @param string HTML-escaped value to print
         * @param string link to foreign key
         * @param array single field returned from fields()
         * @param array original value before applying editVal() and escaping
         * @return string
         */
        function selectVal($val, $link, $field, $original)
        {
            $settings = $this->parseComment($field['comment']);
            if ($settings->type == 'image') {
                if (file_exists($this->baseDir . '/' . $val)) {
                    $size = getimagesize($this->baseDir . '/' . $val);
                    $filesize = $this->formatBytes(filesize($this->baseDir . '/' . $val), 0);
                    return '<img src="' . $this->baseUrl . '/' . $val . '" /><br />' . $size[0] . 'x' . $size[1] . 'px, '.$filesize;
                }
                return '';
            }

            if ($settings->type == 'multi-input') {
                $decoded = @json_decode($original, true);
                if (!is_array($decoded)) {
                    return '';
                }

                $return = '<ul style="padding-left: 15px">';
                foreach ($decoded as $input) {
                    $return .= "<li>{$input}</li>";
                }
                $return .= '</ul>';
                return $return;
            }

            $return = ($val === null ? "<i>NULL</i>" : (preg_match("~char|binary~", $field["type"]) && !preg_match("~var~", $field["type"]) ? "<code>$val</code>" : $val));
            if (preg_match('~blob|bytea|raw|file~', $field["type"]) && !is_utf8($val)) {
                $return = "<i>" . lang('%d byte(s)', strlen($original)) . "</i>";
            }
            if (preg_match('~json~', $field["type"])) {
                $return = "<code class='jush-js'>$return</code>";
            }
            return ($link ? "<a href='" . h($link) . "'" . (is_url($link) ? " rel='noreferrer'" : "") . ">$return</a>" : $return);
        }

        function formatBytes($bytes, $precision = 2) {
            $units = array('B', 'kB', 'MB', 'GB', 'TB');

            $bytes = max($bytes, 0);
            $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
            $pow = min($pow, count($units) - 1);

            // Uncomment one of the following alternatives
            $bytes /= pow(1024, $pow);
            // $bytes /= (1 << (10 * $pow));

            return round($bytes, $precision) . '' . $units[$pow];
        }
    }

    $admin = new AdminerAdmin;
    $admin->baseDir = public_path('images');
    $admin->baseUrl = asset('images');
    $admin->database = env('DB_DATABASE');
    $admin->credentials = array(env('DB_HOST'), env('DB_USERNAME'), env('DB_PASSWORD'));
    return $admin;
}