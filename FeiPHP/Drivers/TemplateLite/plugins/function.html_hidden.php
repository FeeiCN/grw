<?php
/**
 * template_lite {html_hidden} function plugin
 *
 * Type:     function
 * Name:     html_hidden
 * Purpose:  Creates a hidden box
 * Input:
 *           - name = the name of the hidden field
 *           - value = the value of the hidden field
 * Author:   Paul Lockaby <paul@paullockaby.com>
 */
function tpl_function_html_hidden($params, &$tpl)
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
					$tpl->trigger_error("html_hidden: attribute '$_key' cannot be an array");
				}
		}
	}

	if (!isset($name) || empty($name))
	{
		$tpl->trigger_error("html_input: missing 'name' parameter");
		return;
	}

	$toReturn = '<input type="hidden" name="' . tpl_escape_chars($name) . '" value="' . tpl_escape_chars($value) . '" ' . $extra . ' />';
	return $toReturn;
}
?>