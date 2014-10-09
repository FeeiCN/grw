<?php
/**
 * template_lite strip modifier plugin
 *
 * Type:     modifier
 * Name:     strip
 * Purpose:  Removes all repeated spaces, newlines, tabs
 *           with a single space or supplied character
 * Credit:   Taken from the original Smarty
 *           http://smarty.php.net
 */
function tpl_modifier_strip($string, $replace = ' ')
{
	return preg_replace('!\s+!', $replace, $string);
}
?>