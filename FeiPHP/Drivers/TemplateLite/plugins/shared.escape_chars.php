<?php
/**
 * template_lite tpl_escape_chars function
 *
 */

function tpl_escape_chars($string)
{
	if(!is_array($string))
	{
		$string = preg_replace('!&(#?\w+);!', '%%%TEMPLATE_START%%%\\1%%%TEMPLATE_END%%%', $string);
		$string = htmlspecialchars($string);
		$string = str_replace(array('%%%TEMPLATE_START%%%','%%%TEMPLATE_END%%%'), array('&',';'), $string);
	}
	return $string;
}

?>
