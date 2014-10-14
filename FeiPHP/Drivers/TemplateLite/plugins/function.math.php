<?php

/*
 * Template Lite plugin
 * -------------------------------------------------------------
 * Type:     function
 * Name:     math
 * Purpose:  handle math computations in template
 * Taken from the original Smarty
 * http://smarty.php.net
 * -------------------------------------------------------------
 */
function tpl_function_math($params, &$template_object)
{
    // be sure equation parameter is present
    if (empty($params['equation']))
	{
        $template_object->trigger_error("math: missing equation parameter");
        return;
    }

    $equation = $params['equation'];

    // make sure parenthesis are balanced
    if (substr_count($equation,"(") != substr_count($equation,")"))
	{
        $template_object->trigger_error("math: unbalanced parenthesis");
        return;
    }

    // match all vars in equation, make sure all are passed
    preg_match_all("![a-zA-Z][a-zA-Z0-9_]*!",$equation, $match);
    $allowed_funcs = array('int','abs','ceil','cos','exp','floor','log','log10',
                           'max','min','pi','pow','rand','round','sin','sqrt','srand','tan');

    foreach($match[0] as $curr_var)
	{
        if (!in_array($curr_var,array_keys($params)) && !in_array($curr_var, $allowed_funcs))
		{
            $template_object->trigger_error("math: parameter $curr_var not passed as argument");
            return;
        }
    }

    foreach($params as $key => $val)
	{
        if ($key != "equation" && $key != "format" && $key != "assign")
		{
            // make sure value is not empty
            if (strlen($val)==0)
			{
                $template_object->trigger_error("math: parameter $key is empty");
                return;
            }
            if (!is_numeric($val))
			{
                $template_object->trigger_error("math: parameter $key: is not numeric");
                return;
            }
            $equation = preg_replace("/\b$key\b/",$val, $equation);
        }
    }

    eval("\$template_object_math_result = ".$equation.";");

    if (empty($params['format']))
	{
        if (empty($params['assign']))
		{
            return $template_object_math_result;
        }
		else
		{
            $template_object->assign($params['assign'],$template_object_math_result);
        }
    }
	else
	{
        if (empty($params['assign']))
		{
            printf($params['format'],$template_object_math_result);
        }
		else
		{
            $template_object->assign($params['assign'],sprintf($params['format'],$template_object_math_result));
        }
    }
}

?>
