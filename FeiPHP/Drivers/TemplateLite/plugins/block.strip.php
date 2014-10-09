<?php
/**
 * template_lite {strip}{/strip} block plugin
 *
 * Type:     block function
 * Name:     strip
 * Purpose:  strip unwanted white space from text
 * Credit:   Taken from the original Smarty
 *           http://smarty.php.net
 */
function tpl_block_strip($params, $content, &$tpl)
{
	$_strip_search = array(
		"![\t ]+$|^[\t ]+!m",		// remove leading/trailing space chars
		'%[\r\n]+%m',			// remove CRs and newlines
	);
	$_strip_replace = array(
		'',
		'',
	);
	return preg_replace($_strip_search, $_strip_replace, $content);
}
?>