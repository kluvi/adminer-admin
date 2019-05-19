<?php

namespace kluvi\AdminerAdmin\Plugins;

class HideFunctions extends AbstractAdminPlugin
{
    function head()
    {
        ?>
        <style>
            #table thead tr:first-child td:first-child a,
            #table [type=checkbox],
            p.count label,
            input[name="insert"] {
                display: none;
            }
        </style>
        <?php
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
}