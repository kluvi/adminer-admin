<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldRichEditor extends AbstractAdminPlugin
{
    protected $scriptsPrinted = false;
    protected $scriptsPrintedCkeditor = false;

    function editInput($table, $field, $attrs, $value)
    {
        $settings = $this->parseComment($field['comment']);

        if ($settings->type == 'rich' || $this->getFromType($settings, $table) == 'rich') {
            if (!$this->scriptsPrinted) {
                ?>
                <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.11.1/trumbowyg.min.js"></script>
                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.11.1/langs/cs.min.js"></script>
                <!--                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.11.1/plugins/base64/trumbowyg.base64.min.js"></script>-->
                <!--                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.11.1/plugins/cleanpaste/trumbowyg.cleanpaste.min.js"></script>-->
                <!--                <script src="https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.11.1/plugins/history/trumbowyg.history.min.js"></script>-->
                <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.11.1/ui/trumbowyg.min.css"/>

                <div id="trumbowyg-icons">
                    <?php echo file_get_contents('https://cdnjs.cloudflare.com/ajax/libs/Trumbowyg/2.11.1/ui/icons.svg'); ?>
                </div>
                <script type="text/javascript">
                    $(document).ready(function () {
                        $('textarea.rich').closest('td').css({background: 'white'});
                        $('textarea.rich').trumbowyg({
                            removeformatPasted: true,
                            // autogrow: true,
                            // autogrowOnEnter: true,
                            lang: 'cs'
                        });
                    });
                </script>
                <?php
                $this->scriptsPrinted = true;
            }
            $return = '<textarea name="fields[' . $field['field'] . ']" class="rich">' . $value . '</textarea>';
            return $return;
        }

        if ($settings->type == 'ckeditor' || $this->getFromType($settings, $table) == 'ckeditor') {
            if (!$this->scriptsPrinted) {
                echo '<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>';
                echo '<script src="https://cdn.ckeditor.com/ckeditor5/12.0.0/classic/ckeditor.js"></script>';
                echo '<script src="' . asset('ckfinder/ckfinder.js') . '"></script>';
                $this->scriptsPrinted = true;
            }
            ?>

            <script type="text/javascript">
                $(document).ready(function () {
                    $('textarea.rich').closest('td').css({background: 'white'});
                    ClassicEditor
                        .create(document.querySelector('#rich_<?php echo $field['field']; ?>'), {
                            ckfinder: {
                                uploadUrl: 'ckfinder/core/connector/php/connector.php?command=QuickUpload&type=Files&responseType=json',
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
                        .then(editor = > {
                        console.log(editor);
                })
                .
                    catch(error = > {
                        console.error(error);
                })
                    ;
                });
            </script>
            <?php
            $return = '<textarea name="fields[' . $field['field'] . ']" class="rich-ckeditor" id="rich_' . $field['field'] . '" style="min-height: 400px">' . $value . '</textarea>';
            return $return;
        }
    }
}