<?php
/**
 * template_lite lower modifier plugin
 *
 * Type:     modifier
 * Name:     lower
 * Purpose:  Wrapper for the PHP 'strtolower' function
 */
function tpl_modifier_lower($string)
{
	return strtolower($string);
}
?>