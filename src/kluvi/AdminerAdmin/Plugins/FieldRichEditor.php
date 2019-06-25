<?php

namespace kluvi\AdminerAdmin\Plugins;


use Illuminate\Support\Facades\Route;

class FieldRichEditor extends AbstractAdminPlugin
{
    protected $scriptsPrinted = false;
    protected $scriptsPrintedCkeditor = false;
    protected $jqueryPrinted = false;

    function editInput($table, $field, $attrs, $value)
    {
        $settings = $this->parseComment($field['comment']);

        if ($settings->type == 'rich' || $this->getFromType($settings, $table) == 'rich') {
            if(!$this->jqueryPrinted) {
                echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';
                $this->jqueryPrinted = true;
            }
            if (!$this->scriptsPrinted) {
                $canUpload = Route::has('adminer-admin-upload-file');
                ?>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/trumbowyg.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/langs/cs.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/colors/trumbowyg.colors.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/emoji/trumbowyg.emoji.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/fontfamily/trumbowyg.fontfamily.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/fontsize/trumbowyg.fontsize.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/table/trumbowyg.table.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/template/trumbowyg.template.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/upload/trumbowyg.upload.min.js"></script>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/ui/trumbowyg.min.css"/>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/colors/ui/trumbowyg.colors.min.css"/>
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/emoji/ui/trumbowyg.emoji.min.css" />
                <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/plugins/table/ui/trumbowyg.table.min.css" />

                <div id="trumbowyg-icons">
                    <?php echo file_get_contents('https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.18.0/ui/icons.svg'); ?>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('textarea.rich').closest('td').css({background: 'white'});
                        $('textarea.rich').trumbowyg({
                            removeformatPasted: true,
                            // autogrow: true,
                            // autogrowOnEnter: true,
                            lang: 'cs',
                            changeActiveDropdownIcon: true,
                            hideButtonTexts: true,
                            resetCss: true,
                            removeformatPasted: false,
                            tagsToRemove: ['script', 'link'],
                            imageWidthModalEdit: true,
                            minimalLinks: true,
                            btns: [
                                ['viewHTML'],
                                // ['template'],
                                ['undo', 'redo'], // Only supported in Blink browsers
                                ['formatting'],
                                // ['fontfamily'],
                                // ['fontsize'],
                                ['strong', 'em', 'del'],
                                ['superscript', 'subscript'],
                                // ['foreColor', 'backColor'],
                                ['link'],
                                ['insertImage'<?php echo ($canUpload ? ", 'upload'" : "") ?>],
                                ['table'],
                                ['emoji'],
                                ['justifyLeft', 'justifyCenter', 'justifyRight', 'justifyFull'],
                                ['unorderedList', 'orderedList'],
                                ['horizontalRule'],
                                ['removeformat'],
                                ['fullscreen']
                            ],
                            plugins: {
                                table: {
                                    rows: 8,
                                    columns: 8,
                                    styler: 'table'
                                },
                                templates: [
                                    {
                                        name: 'Template 1',
                                        html: '<p>I am a template!</p>'
                                    },
                                    {
                                        name: 'Template 2',
                                        html: '<p>I am a different template!</p>'
                                    }
                                ],
                                <?php if($canUpload): ?>
                                upload: {
                                    serverPath: '<?php echo route('adminer-admin-upload-file') ?>', // The URL to the server which catch the upload request
                                    fileFieldName: 'fileToUpload', // The POST property key associated to the upload file
                                }
                                <?php endif; ?>
                            }
                        });
                    });
                </script>
                <?php
                $this->scriptsPrinted = true;
            }
            $value = str_replace('@@rich_editor_base_url@@', $this->config['baseUrl'], $value);
            $return = '<textarea name="fields[' . $field['field'] . ']" class="rich">' . $value . '</textarea>';
            return $return;
        }

        if ($settings->type == 'ckeditor' || $this->getFromType($settings, $table) == 'ckeditor') {
            if(!$this->jqueryPrinted) {
                echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';
                $this->jqueryPrinted = true;
            }
            if (!$this->scriptsPrintedCkeditor) {
                echo '<script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/classic/ckeditor.js"></script>';
                echo '<script src="' . asset('ckfinder/ckfinder.js') . '"></script>';
                $this->scriptsPrintedCkeditor = true;
            }
            ?>

            <script type="text/javascript">
                $(document).ready(function () {
                    $('textarea.rich').closest('td').css({background: 'white'});
                    ClassicEditor
                        .create(document.querySelector('#rich_<?php echo $field['field']; ?>'), {
                            ckfinder: {
                                uploadUrl: '<?php echo asset('ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json'); ?>',
                            },
                            toolbar: [
                                'heading', '|',
                                'bold', 'italic', 'blockquote', '|',
                                'ckfinder', 'link', /*'mediaembed',*/ '|',
                                'numberedlist', 'bulletedlist', '|',
                                'inserttable', 'tablecolumn', 'tablerow', 'mergetablecells', '|',
                                'undo', 'redo'
                            ]
                        })
                        .then(editor => {
                            // console.log(editor);
                        })
                        .catch(error => {
                            console.error(error);
                        });
                });
            </script>
            <?php
            $value = str_replace('@@rich_editor_base_url@@', $this->config['baseUrl'], $value);
            $return = '<textarea name="fields[' . $field['field'] . ']" class="rich-ckeditor" id="rich_' . $field['field'] . '" style="min-height: 400px">' . $value . '</textarea>';
            return $return;
        }
    }

    function processInput($field, $value, $function = "")
    {
        $settings = $this->parseComment($field['comment']);
        if ($settings->type == 'rich' || $this->getFromType($settings, $_GET['edit']) == 'rich') {
            $value = preg_replace('|/assets-([a-z0-9]+/)|i', '/', $value);
            $value = str_replace($this->config['baseUrl'], '@@rich_editor_base_url@@', $value);
            return q($value);
        }
    }
}