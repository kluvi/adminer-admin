<?php

namespace kluvi\AdminerAdmin\Plugins;


class FieldMultiInput extends AbstractAdminPlugin
{
    protected $scriptsPrinted = false;

    function editInput($table, $field, $attrs, $value)
    {
        $settings = $this->parseComment($field['comment']);

        if ($settings->type == 'multi-input') {
            if (!$this->scriptsPrinted) {
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
                </style>
                <?php
                $this->scriptsPrinted;
            }
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
    }

    function processInput($field, $value, $function = "")
    {
        $settings = $this->parseComment($field['comment']);
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
    }

    function selectVal($val, $link, $field, $original)
    {
        $settings = $this->parseComment($field['comment']);
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
    }
}