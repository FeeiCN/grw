<?php

/*
 * Template Lite plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     popup_init
 * Purpose:  initialize overlib
 * Taken from the original Smarty
 * http://smarty.php.net
 * -------------------------------------------------------------
 */
function tpl_function_popup_init($params, &$template_object)
{
	$zindex = 1000;
    if (!empty($params['zindex']))
	{
		$zindex = $params['zindex'];
	}

    if (!empty($params['src']))
	{
    	return '<div id="overDiv" style="position:absolute; visibility:hidden; z-index:'.$zindex.';"></div>' . "\n"
         . '<script type="text/javascript" language="JavaScript" src="'.$params['src'].'"></script>' . "\n";
    }
	else
	{
        $template_object->trigger_error("popup_init: missing src parameter");
    }
}

?>
