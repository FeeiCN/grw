<?php
/**
 * template_lite replace modifier plugin
 *
 * Type:     modifier
 * Name:     replace
 * Purpose:  Wrapper for the PHP 'str_replace' function
 * Credit:   Taken from the original Smarty
 *           http://smarty.php.net
 */
function tpl_modifier_replace($string, $search, $replace)
{
	return str_replace($search, $replace, $string);
}
?>