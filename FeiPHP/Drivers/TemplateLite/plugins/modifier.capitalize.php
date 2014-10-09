<?php
/**
 * template_lite capitalize modifier plugin
 *
 * Type:     modifier
 * Name:     capitalize
 * Purpose:  Wrapper for the PHP 'ucwords' function
 */
function tpl_modifier_capitalize($string)
{
	return ucwords($string);
}
?>