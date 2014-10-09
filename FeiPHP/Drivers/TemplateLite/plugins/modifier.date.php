<?php
/**
 * template_lite date modifier plugin
 *
 * Type:     modifier
 * Name:     date
 * Purpose:  formats a date given a UNIX timestamp, based on the
 *           PHP "date" function
 * Input:
 *         - string: input date string
 *         - format: date format for output
 *         - default_date: default date if $string is empty
 */
function tpl_modifier_date($string, $format="r", $default_date=null)
{
	if($string != '')
	{
		return date($format, tpl_make_timestamp($string));
	}
	elseif (isset($default_date) && $default_date != '')
	{		
		return date($format, tpl_make_timestamp($default_date));
	}
	else
	{
		return;
	}
}

if(!function_exists('tpl_make_timestamp'))
{
	function tpl_make_timestamp($string)
	{
		if(empty($string))
		{
			$string = "now";
		}
		$time = strtotime($string);
		if (is_numeric($time) && $time != -1)
		{
			return $time;
		}

		// is mysql timestamp format of YYYYMMDDHHMMSS?
		if (is_numeric($string) && strlen($string) == 14)
		{
			$time = mktime(substr($string,8,2),substr($string,10,2),substr($string,12,2),substr($string,4,2),substr($string,6,2),substr($string,0,4));
			return $time;
		}

		// couldn't recognize it, try to return a time
		$time = (int) $string;
		if ($time > 0)
		{
			return $time;
		}
		else
		{
			return time();
		}
	}
}
?>