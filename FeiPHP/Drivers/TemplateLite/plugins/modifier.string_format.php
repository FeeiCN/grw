<?php
/**
 * template_lite string_format modifier plugin
 *
 * Type:     modifier
 * Name:     string_format
 * Purpose:  Wrapper for the PHP 'vsprintf' function
 */
function tpl_modifier_string_format()
{
	$_args = func_get_args();
	$string = array_shift($_args);
	return vsprintf($string, $_args);
}
?>