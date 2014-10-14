<?php
/**
 * Template_Lite {html_textbox} function plugin
 *
 * Type:     function
 * Name:     html_textbox
 * Purpose:  Creates a textbox
 * Input:
 *           - name = the name of the textbox
 *           - rows = optional number of rows in the textbox
 *           - cols = optional number of columns in the textbox
 *           - value = optional preset value to put in the textbox
 * Author:   Paul Lockaby <paul@paullockaby.com>
 */
function tpl_function_html_textbox($params, &$tpl)
{
	require_once("shared.escape_chars.php");
	$name = null;
	$value = '';
	$extra = '';

	foreach($params as $_key => $_value)
	{
		switch($_key)
		{
			case 'name':
			case 'value':
				$$_key = $_value;
				break;
			default:
				if(!is_array($_key))
				{
					$extra .= ' ' . $_key . '="' . tpl_escape_chars($_value) . '"';
				}
				else
				{
					$tpl->trigger_error("html_textbox: attribute '$_key' cannot be an array");
				}
		}
	}

	if (!isset($name) || empty($name))
	{
		$tpl->trigger_error("html_textbox: missing 'name' parameter");
		return;
	}

	$toReturn = '<textarea name="' . tpl_escape_chars($name) . '" ' . $extra . '>' . tpl_escape_chars($value) . '</textarea>';
	return $toReturn;
}
?>