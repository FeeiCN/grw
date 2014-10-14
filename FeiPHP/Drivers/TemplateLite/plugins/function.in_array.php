<?php 
/** 
 * template_lite in_array plugin 
 * 
 * Type:     function 
 * Name:     in_array 
 * Purpose:  Checks to see if there is an item in the array that matches and returns the returnvalue if true. 
 */ 
function tpl_function_in_array($params, &$tpl)
{
	extract($params);

	if (is_array($array))
	{
		if (in_array($match, $array))
		{
			return $returnvalue;
		}
	}
}
?>