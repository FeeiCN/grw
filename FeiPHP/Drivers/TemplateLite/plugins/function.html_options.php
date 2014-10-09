<?php
/*
 * template_lite plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     html_options
 * Purpose:  prints out the options for an html_select item
 * Credit:   Taken from the original Smarty
 *           http://smarty.php.net
 * -------------------------------------------------------------
 */
function tpl_function_html_options($params, &$tpl)
{
	require_once("shared.escape_chars.php");
	$name = null;
	$options = null;
	$selected = array();
	$extra = '';
  
	foreach($params as $_key => $_val)
	{
		switch($_key)
		{
			case 'name':
				$$_key = (string)$_val;
				break;
			case 'options':
				$$_key = (array)$_val;
				break;
			case 'values':
			case 'output':
				$$_key = array_values((array)$_val);
				break;
			case 'selected':
				$$_key = array_values((array)$_val);      
				break;
			default:
				if(!is_array($_key))
				{
					$extra .= ' ' . $_key . '="' . tpl_escape_chars($_val) . '"';
				}
				else
				{
					$tpl->trigger_error("html_select: attribute '$_key' cannot be an array");
				}
				break;
		}
	}

	$_html_result = '';
	if (is_array($options))
	{
		foreach ($options as $_key=>$_val)
		{
			$_html_result .= tpl_function_html_options_optoutput($tpl, $_key, $_val, $selected);      
		}
	}
	else
	{
		foreach ((array)$values as $_i=>$_key)
		{
			$_val = isset($output[$_i]) ? $output[$_i] : '';
			$_html_result .= tpl_function_html_options_optoutput($tpl, $_key, $_val, $selected);
		}
	 }

	if(!empty($name))
	{
		$_html_result = '<select name="' . tpl_escape_chars($name) . '"' . $extra . '>' . "\n" . $_html_result . '</select>' . "\n";
	}

	return $_html_result;
}

function tpl_function_html_options_optoutput(&$tpl, $key, $value, $selected)
{
	if(!is_array($value))
	{
		$_html_result = '<option label="' . tpl_escape_chars($value) . '" value="' . tpl_escape_chars($key) . '"';
		if (in_array($key, $selected))
		{
			$_html_result .= ' selected="selected"';
		}
		$_html_result .= '>' . tpl_escape_chars($value) . '</option>' . "\n";
	}
	else
	{
		$_html_result = tpl_function_html_options_optgroup($tpl, $key, $value, $selected);
	}
	return $_html_result;
}

function tpl_function_html_options_optgroup(&$tpl, $key, $values, $selected)
{
	$optgroup_html = '<optgroup label="' . tpl_escape_chars($key) . '">' . "\n";
	foreach ($values as $key => $value)
	{
		$optgroup_html .= tpl_function_html_options_optoutput($tpl, $key, $value, $selected);
	}
	$optgroup_html .= "</optgroup>\n";
	return $optgroup_html;
}

?>