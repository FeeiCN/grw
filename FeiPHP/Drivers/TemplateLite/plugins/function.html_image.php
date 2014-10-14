<?php
/**
 * template_lite {html_image} function plugin
 *
 * Type:     function
 * Name:     html_image
 * Purpose:  Outputs an image tag along with resized height/width
 * Input:
 *           - url = the url of the picture
 *           - width = optional width
 *           - height = optional height
 *           - limit = boolean - will resize image to the above height
 *                     and width if the above height and width are
 *                     smaller than the real height and width
 *           - border = optional size of the border, default is "0"
 *           - alt = optional alternate text to display
 * Examples:<br>
 * <pre>
 * {html_image url="http://www.yoursite.com/image.jpg"}
 * {html_image url="images/me.gif" alt="A picture of me!"}
 * </pre>
 * Author:   Paul Lockaby <paul@paullockaby.com>
 */
function tpl_function_html_image($params, &$tpl)
{
	require_once("shared.escape_chars.php");
	$alt = '';
	$url = '';
	$height = '';
	$width = '';
	$extra = '';
	$prefix = '';
	$suffix = '';
	$basedir = isset($_SERVER['DOCUMENT_ROOT']) ? $_SERVER['DOCUMENT_ROOT'] : '';
	foreach($params as $_key => $_val)
	{
		switch($_key)
		{
			case 'url':
			case 'height':
			case 'width':
				$$_key = $_val;
				break;
			case 'limit':
				$$_key = true;
				break;
			case 'alt':
				if(!is_array($_val))
				{
					$$_key = tpl_escape_chars($_val);
				}
				else
				{
					$tpl->trigger_error("html_image: attribute '$_key' cannot be an array");
				}
				break;
			case 'link':
			case 'href':
				$prefix = '<a href="' . $_val . '">';
				$suffix = '</a>';
				break;
			default:
				if(!is_array($_val))
				{
					$extra .= ' '.$_key.'="'.template_function_escape_special_chars($_val).'"';
				}
				else
				{
					$template_object->trigger_error("html_image: extra attribute '$_key' cannot be an array", E_USER_NOTICE);
				}
				break;
			}
	}

	if (empty($url))
	{
		$template_object->trigger_error("html_image: missing 'url' parameter", E_USER_NOTICE);
		return;
	}

	if (substr($file,0,1) == '/')
	{
		$_image_path = $basedir . $file;
	}
	else
	{
		$_image_path = $file;
	}

	// 0 = width, 1 = height
	if ($size = @getimagesize($url))
	{
		if (empty($limit) || $limit == false)
		{
			// only a height was specified; we will fill in the width
			if (!empty($height))
			{
				$width = $size[0];
			}
			// only a width was specified; we will fill in the height
			if (!empty($width))
			{
				$height = $size[1];
			}
			// neither a height nor a width was specified; we will fill in both
			if (empty($width) && empty($height))
			{
				$width = $size[0];
				$height = $size[1];
			}
		}
		else
		{
			if ((!empty($width) && ($size[0] > $width)) || (!empty($height) && ($size[1] > $height)))
			{
				if (!empty($height) && !empty($width))
				{
					// compare the ratios to determine how much each dimension needs to be changed

					// this will return the width if the height is set to specified
					$bth_width = round($size[0]*($height/$size[1]));

					// this will return the height if the width is set to specified
					$bth_height = round($size[1]*($width/$size[0]));

					// first we set the width to the max and see how big the height will be
					if (!($bth_height > $height))
					{
						// returned height is acceptable (i.e. less than specified)
						$fin1_height = $bth_height;
						$fin1_width = $width;
					}

					// now we set the height to the max and see how big the width will be
					if (!($bth_width > $width))
					{
						// returned width is acceptable (i.e. less than specified)
						$fin2_height = $height;
						$fin2_width = $bth_width;
					}

					// check to see if both of them went through
					if (isset($fin1_height) && isset($fin1_width) && isset($fin2_height) && isset($fin2_width))
					{
						// now check the difference between abs($fin1_height-$fin1_width) and abs($fin2_height-$fin2_width)
						// since we obviously want the larger image, take whichever one has the smaller difference
						if (abs($fin1_height - $fin1_width) < abs($fin2_height - $fin2_width))
						{
							$new_height = $fin1_height;
							$new_width = $fin1_width;
						}
						else
						{
							$new_height = $fin2_height;
							$new_width = $fin2_width;
						}
					}
					else
					{
						// since $new_height and $new_width weren't set above, we have to set them here
						if (isset($fin1_height) && isset($fin1_width))
						{
							$new_height = $fin1_height;
							$new_width = $fin1_width;
						}
						else
						{
							$new_height = $fin2_height;
							$new_width = $fin2_width;
						}
					}
				}
				else
				{
					// only a height or only a width was specified
					// much easier
					if (!empty($height))
					{
						// working with only a height now
						$new_height = $height;
						$new_width = round($size[0]*($height/$size[1]));
					}
					if (!empty($width))
					{
						// working with only a width now
						$new_height = round($size[1]*($width/$size[0]));
						$new_width = $width;
					}
				}
				$width = $new_width;
				$height = $new_height;
			}
			else
			{
				$width = $size[0];
				$height = $size[1];
			}
		}
	}

	return $prefix . '<img src="' . $url . '" alt="' . $alt . '" width="' . $width . '" height="' . $height . '"'.$extra.' />' . $suffix;
}
?>