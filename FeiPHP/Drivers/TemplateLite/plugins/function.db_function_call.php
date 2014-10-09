<?php
/*
 * Template Lite plugin
 * -------------------------------------------------------------
 * Type:	 function
 * Name:	 db_function_call
 * Purpose:  Interface with ADOdb Lite to query database.
 *
 * db_object = Database object
 * db_function = Database function to execute
 * db_query = query string to pass to the database
 * db_assign = variable name to assign result data
 * db_errornumber_assign = variable name to assign the database error number
 * db_error_assign = the variable name to assign the database error message
 * db_EOF_assign = the variable name to assign the database end of file flag
 * -------------------------------------------------------------
 */
function tpl_function_db_function_call($params, &$template_object)
{
	if (empty($params['db_object']))
	{
		$template_object->trigger_error("db_function_call: missing db_object parameter");
		return;
	}

	if (!is_object($params['db_object']))
	{
		$template_object->trigger_error("db_function_call: db_object isn't an object");
		return;
	}

	$db = $params['db_object'];

	if (empty($params['db_assign']))
	{
		$template_object->trigger_error("db_function_call: missing db_assign parameter");
		return;
	}

	if (empty($params['db_function']))
	{
		$template_object->trigger_error("db_function_call: missing db_function parameter");
		return;
	}

	$db_function = $params['db_function'];

	$result = $db->$db_function($params['db_query']);

	$template_object->assign($params['db_assign'], $result);

	if (!empty($params['db_errornumber_assign']))
	{
		$template_object->assign($params['db_errornumber_assign'], $db->ErrorNo());
	}

	if (!empty($params['db_error_assign']))
	{
		$template_object->assign($params['db_error_assign'], $db->ErrorMsg());
	}

	if (!empty($params['db_EOF_assign']))
	{
		$template_object->assign($params['db_EOF_assign'], $result->EOF);
	}
}
?>
