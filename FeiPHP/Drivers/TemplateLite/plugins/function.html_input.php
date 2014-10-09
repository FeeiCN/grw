<?php
/**
 * template_lite {html_input} function plugin
 *
 * Type:     function
 * Name:     html_input
 * Purpose:  Creates an input text or password box
 * Input:
 *           - name = the name of the textbox
 *           - password = boolean - if set, this box will be a password box
 *           - value = optional default value for the input box
 *           - size = optional size for the input box
 *           - length = optional maxlength for the input box
 * Author:   Paul Lockaby <paul@paullockaby.com>
 */
function tpl_function_html_input($params, &$tpl)
{
	require_once("shared.escape_chars.php");
	$name = null;
	$value = '';
	$password = false;
	$extra = '';

	foreach($params as $_key => $_value)
	{
		switch($_key)
		{
			case 'name':
			case 'value':
				$$_key = $_value;
				break;
			case 'password':
				$$_key = true;
				break;
			default:
				if(!is_array($_key))
				{
					$extra .= ' ' . $_key . '="' . tpl_escape_chars($_value) . '"';
				}
				else
				{
					$tpl->trigger_error("html_input: attribute '$_key' cannot be an array");
				}
		}
	}

	if (!isset($name) || empty($name))
	{
		$tpl->trigger_error("html_input: missing 'name' parameter");
		return;
	}
	
	$toReturn = '<input type="';
	$toReturn .= $password ? 'password' : 'text';
	$toReturn .= '" name="' . tpl_escape_chars($name) . '" value="' . tpl_escape_chars($value) . '" ' . $extra . ' />';
	return $toReturn;
}
?>