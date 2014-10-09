<?php
/**
 * 全局函数
 */

/**
 * 返回经addslashes处理过的字符串或数组:使用反斜线引用字符串
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_addslashes($string)
{
	if (!is_array($string)) return addslashes($string);
	foreach ($string as $key => $val) $string[$key] = new_addslashes($val);
	return $string;
}

/**
 * 返回经stripslashes处理过的字符串或数组:还原反斜线引用字符串
 * @param $string 需要处理的字符串或数组
 * @return mixed
 */
function new_stripslashes($string)
{
	if (!is_array($string)) return stripslashes($string);
	foreach ($string as $key => $val) $string[$key] = new_stripslashes($val);
	return $string;
}

/**
 * htmlspecialchars:特殊字元转成HTML格式
 * & &amp; " &quot; < &lt; > &lt;
 * @param $obj 需要处理的字符串或数组
 * @return mixed
 */
function new_html_special_chars($string)
{
	if (!is_array($string)) return htmlspecialchars($string);
	foreach ($string as $key => $val) $string[$key] = new_html_special_chars($val);
	return $string;
}

/**
 * 安全过滤函数
 * @param $string 需要处理的字符串或数组
 * @return string
 */
function safe_replace($string)
{
	if (!is_array($string)) {
		$string = str_replace('%20', '', $string); #space
		$string = str_replace('%27', '', $string); #'
		$string = str_replace('%2527', '', $string); #''
		$string = str_replace('*', '', $string);
		$string = str_replace('"', '&quot;', $string);
		$string = str_replace("'", '', $string);
		$string = str_replace('"', '', $string);
		$string = str_replace(';', '', $string);
		$string = str_replace('<', '&lt;', $string);
		$string = str_replace('>', '&gt;', $string);
		$string = str_replace("{", '', $string);
		$string = str_replace('}', '', $string);
		$string = str_replace('\\', '', $string);
		$string = remove_xss($string);
	} else {
		foreach ($string as $key => $val) {
			$string[$key] = str_replace('%20', '', $val);
			$string[$key] = str_replace('%27', '', $string[$key]);
			$string[$key] = str_replace('%2527', '', $string[$key]);
			$string[$key] = str_replace('*', '', $string[$key]);
			$string[$key] = str_replace('"', '&quot;', $string[$key]);
			$string[$key] = str_replace("'", '', $string[$key]);
			$string[$key] = str_replace('"', '', $string[$key]);
			$string[$key] = str_replace(';', '', $string[$key]);
			$string[$key] = str_replace('<', '&lt;', $string[$key]);
			$string[$key] = str_replace('>', '&gt;', $string[$key]);
			$string[$key] = str_replace("{", '', $string[$key]);
			$string[$key] = str_replace('}', '', $string[$key]);
			$string[$key] = str_replace('\\', '', $string[$key]);
			$string       = remove_xss($string);
		}

	}
	return $string;
}

/**
 * xss过滤函数
 * @param $string
 * @return string
 */
function remove_xss($string)
{
	$string = preg_replace('/[\x00-\x08\x0B\x0C\x0E-\x1F\x7F]+/S', '', $string);

	$parm1 = Array('javascript', 'vbscript', 'expression', 'applet', 'meta', 'xml', 'blink', 'link', 'script', 'embed', 'object', 'iframe', 'frame', 'frameset', 'ilayer', 'layer', 'bgsound', 'title', 'base');

	$parm2 = Array('onabort', 'onactivate', 'onafterprint', 'onafterupdate', 'onbeforeactivate', 'onbeforecopy', 'onbeforecut', 'onbeforedeactivate', 'onbeforeeditfocus', 'onbeforepaste', 'onbeforeprint', 'onbeforeunload', 'onbeforeupdate', 'onblur', 'onbounce', 'oncellchange', 'onchange', 'onclick', 'oncontextmenu', 'oncontrolselect', 'oncopy', 'oncut', 'ondataavailable', 'ondatasetchanged', 'ondatasetcomplete', 'ondblclick', 'ondeactivate', 'ondrag', 'ondragend', 'ondragenter', 'ondragleave', 'ondragover', 'ondragstart', 'ondrop', 'onerror', 'onerrorupdate', 'onfilterchange', 'onfinish', 'onfocus', 'onfocusin', 'onfocusout', 'onhelp', 'onkeydown', 'onkeypress', 'onkeyup', 'onlayoutcomplete', 'onload', 'onlosecapture', 'onmousedown', 'onmouseenter', 'onmouseleave', 'onmousemove', 'onmouseout', 'onmouseover', 'onmouseup', 'onmousewheel', 'onmove', 'onmoveend', 'onmovestart', 'onpaste', 'onpropertychange', 'onreadystatechange', 'onreset', 'onresize', 'onresizeend', 'onresizestart', 'onrowenter', 'onrowexit', 'onrowsdelete', 'onrowsinserted', 'onscroll', 'onselect', 'onselectionchange', 'onselectstart', 'onstart', 'onstop', 'onsubmit', 'onunload');

	$parm = array_merge($parm1, $parm2);

	for ($i = 0; $i < sizeof($parm); $i++) {
		$pattern = '/';
		for ($j = 0; $j < strlen($parm[$i]); $j++) {
			if ($j > 0) {
				$pattern .= '(';
				$pattern .= '(&#[x|X]0([9][a][b]);?)?';
				$pattern .= '|(&#0([9][10][13]);?)?';
				$pattern .= ')?';
			}
			$pattern .= $parm[$i][$j];
		}
		$pattern .= '/i';
		$string = preg_replace($pattern, '', $string);
	}
	return $string;
}

?>
