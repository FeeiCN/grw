<?php
/*
 * Template Lite plugin
 * -------------------------------------------------------------
 * File:     compiler.tplheader.php
 * Type:     compiler
 * Name:     tplheader
 * Purpose:  Output header containing the source file name and
 *           the time it was compiled.
 * -------------------------------------------------------------
 */
function tpl_compiler_tplheader($arguments, &$tpl)
{
    return "\necho '" . $tpl->_file . " compiled at " . date('Y-m-d H:M'). "';";
}
?>