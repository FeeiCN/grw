<?php
/**
 * template_lite default modifier plugin
 *
 * Type:     modifier
 * Name:     default
 * Purpose:  designate default value for empty variables
 * Credit:   Taken from the original Smarty
 *           http://smarty.php.net
 */
function tpl_modifier_default($string, $default = '')
{
	if (!isset($string) || $string === '')
	{
		return $default;
	}
	else
	{
		return $string;
	}
}
?>