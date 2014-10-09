<?php

/**
 * FeiRun  执行用户代码
 */
function FeiRun()
{
	GLOBAL $__controller, $__action;
	// 对路由进行自动执行相关操作
	FeiLaunch("router_prefilter");
	// 对将要访问的控制器类进行实例化
	$handle_controller = FeiClass($__controller, NULL, $GLOBALS['G_Fei']["controller_path"] . '/' . $__controller . ".php");
	// 调用控制器出错将调用路由错误处理函数
	if (!is_object($handle_controller) || !method_exists($handle_controller, $__action)) {
		eval($GLOBALS['G_Fei']["dispatcher_error"]);
		exit;
	}
	// 路由并执行用户代码
	$handle_controller->$__action();
	// 控制器程序运行完毕，进行模板的自动输出
	if (FALSE != $GLOBALS['G_Fei']['view']['auto_display']) {
		$__tplname = $__controller . $GLOBALS['G_Fei']['view']['auto_display_sep'] .
			$__action . $GLOBALS['G_Fei']['view']['auto_display_suffix']; // 拼装模板路径
		$handle_controller->auto_display($__tplname);
	}
	// 对路由进行后续相关操作
	FeiLaunch("router_postfilter");
}

/**
 * dump  格式化输出变量程序
 * @param vars          变量
 * @param output        是否将内容输出
 * @param show_trace    是否将使用FeiError对变量进行追踪输出
 */
function dump($vars, $output = TRUE, $show_trace = FALSE)
{
	// 部署模式下同时不允许查看调试信息的情况，直接退出。
	if (TRUE != Fei_DEBUG && TRUE != $GLOBALS['G_Fei']['allow_trace_onrelease']) return;
	if (TRUE == $show_trace) { // 显示变量运行路径
		$content = FeiError(htmlspecialchars(print_r($vars, TRUE)), TRUE, FALSE);
	} else {
		$content = "<div align=left><pre>\n" . htmlspecialchars(print_r($vars, TRUE)) . "\n</pre></div>\n";
	}
	if (TRUE != $output) {
		return $content;
	} // 直接返回，不输出。
	echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"></head><body>{$content}</body></html>";
	return;
}

/**
 * import  载入包含文件
 * @param filename       需要载入的文件名或者文件路径
 * @param auto_search    载入文件找不到时是否搜索系统路径或文件，搜索路径的顺序为：应用程序包含目录 -> 应用程序Model目录 -> Fei框架包含文件目录
 * @param auto_error     自动提示扩展类载入出错信息
 */
function import($sfilename, $auto_search = TRUE, $auto_error = FALSE)
{
	if (isset($GLOBALS['G_Fei']["import_file"][md5($sfilename)])) return TRUE; // 已包含载入，返回
	// 检查$sfilename是否直接可读
	if (TRUE == @is_readable($sfilename)) {
		require($sfilename); // 载入文件
		$GLOBALS['G_Fei']['import_file'][md5($sfilename)] = TRUE; // 对该文件进行标识为已载入
		return TRUE;
	} else {
		if (TRUE == $auto_search) { // 需要搜索文件
			// 按“应用程序包含目录 -> 应用程序Model目录 -> Fei框架包含文件目录”的顺序搜索文件
			foreach (array_merge($GLOBALS['G_Fei']['include_path'], array($GLOBALS['G_Fei']['model_path']), $GLOBALS['G_Fei']['Fei_include_path']) as $Fei_include_path) {
				// 检查当前搜索路径中，该文件是否已经载入
				if (isset($GLOBALS['G_Fei']["import_file"][md5($Fei_include_path . '/' . $sfilename)])) return TRUE;
				if (is_readable($Fei_include_path . '/' . $sfilename)) {
					require($Fei_include_path . '/' . $sfilename); // 载入文件
					$GLOBALS['G_Fei']['import_file'][md5($Fei_include_path . '/' . $sfilename)] = TRUE; // 对该文件进行标识为已载入
					return TRUE;
				}
			}
		}
	}
	if (TRUE == $auto_error) FeiError("未能找到名为：{$sfilename}的文件");
	return FALSE;
}

/**
 * FeiAccess 数据缓存及存取程序
 * @param method       数据存取模式，取值"w"为存入数据，取值"r"读取数据，取值"c"为删除数据
 * @param name         标识数据的名称
 * @param value        存入的值，在读取数据和删除数据的模式下均为NULL
 * @param life_time    变量的生存时间，默认为永久保存
 */
function FeiAccess($method, $name, $value = NULL, $life_time = -1)
{
	// 使用function_access扩展点
	if ($launch = FeiLaunch("function_access", array('method' => $method, 'name' => $name, 'value' => $value, 'life_time' => $life_time), TRUE)) return $launch;
	// 准备缓存目录和缓存文件名称，缓存文件名称为$name的MD5值，文件后缀为php
	if (!is_dir($GLOBALS['G_Fei']['Fei_cache'])) __mkdirs($GLOBALS['G_Fei']['Fei_cache']);
	$sfile = $GLOBALS['G_Fei']['Fei_cache'] . '/' . $GLOBALS['G_Fei']['Fei_app_id'] . md5($name) . ".php";
	// 对$method进行判断，分别进行读写删的操作
	if ('w' == $method) {
		// 写数据，在$life_time为-1的时候，将增大$life_time值以令$life_time不过期
		$life_time = (-1 == $life_time) ? '300000000' : $life_time;
		// 准备存入缓存文件的数据，缓存文件使用PHP的die();函数以便保证内容安全，
		$value = '<?php die();?>' . (time() + $life_time) . serialize($value); // 数据被序列化后保存
		return file_put_contents($sfile, $value);
	} elseif ('c' == $method) {
		// 清除数据，直接移除改缓存文件
		return @unlink($sfile);
	} else {
		// 读数据，检查文件是否可读，同时将去除缓存数据前部的内容以返回
		if (!is_readable($sfile)) return FALSE;
		$arg_data = file_get_contents($sfile);
		// 获取文件保存的$life_time，检查缓存是否过期
		if (substr($arg_data, 14, 10) < time()) {
			@unlink($sfile); // 过期则移除缓存文件，返回FALSE
			return FALSE;
		}
		return unserialize(substr($arg_data, 24)); // 数据反序列化后返回
	}
}

/**
 * FeiClass  类实例化函数  自动载入类定义文件，实例化并返回对象句柄
 * @param class_name    类名称
 * @param args          类初始化时使用的参数，数组形式
 * @param sdir          载入类定义文件的路径，可以是目录+文件名的方式，也可以单独是目录。sdir的值将传入import()进行载入
 * @param force_inst    是否强制重新实例化对象
 */
function FeiClass($class_name, $args = NULL, $sdir = NULL, $force_inst = FALSE)
{
	// 检查类名称是否正确，以保证类定义文件载入的安全性
	if (preg_match('/[^a-z0-9\-_.]/i', $class_name)) FeiError($class_name . "类名称错误，请检查。");
	// 检查是否该类已经实例化，直接返回已实例对象，避免再次实例化
	if (TRUE != $force_inst) if (isset($GLOBALS['G_Fei']["inst_class"][$class_name])) return $GLOBALS['G_Fei']["inst_class"][$class_name];
	// 如果$sdir不能读取，则测试是否仅路径
	if (NULL != $sdir && !import($sdir) && !import($sdir . '/' . $class_name . '.php')) return FALSE;

	$has_define = FALSE;
	// 检查类定义是否存在
	if (class_exists($class_name, FALSE) || interface_exists($class_name, FALSE)) {
		$has_define = TRUE;
	} else {
		if (TRUE == import($class_name . '.php')) {
			$has_define = TRUE;
		}
	}
	if (FALSE != $has_define) {
		$argString = '';
		$comma     = '';
		if (NULL != $args) for ($i = 0; $i < count($args); $i++) {
			$argString .= $comma . "\$args[$i]";
			$comma = ', ';
		}
		eval("\$GLOBALS['G_Fei']['inst_class'][\$class_name]= new \$class_name($argString);");
		return $GLOBALS['G_Fei']["inst_class"][$class_name];
	}
	FeiError($class_name . "类定义不存在，请检查。");
}

/**
 * FeiError 框架定义的系统级错误提示
 * @param msg       出错信息
 * @param output    是否输出
 * @param stop      是否停止程序
 */
function FeiError($msg, $output = TRUE, $stop = TRUE)
{
	if ($GLOBALS['G_Fei']['Fei_error_throw_exception']) throw new Exception($msg);
	if (TRUE != Fei_DEBUG) {
		error_log($msg);
		if (TRUE == $stop) exit;
	}
	$traces      = debug_backtrace();
	$bufferabove = ob_get_clean();
	require_once($GLOBALS['G_Fei']['Fei_notice_php']);
	if (TRUE == $stop) exit;
}

/**
 * FeiLaunch  执行扩展程序
 * @param configname    扩展程序设置点名称
 * @param launchargs    扩展参数
 * @param return        是否存在返回数据，如需要返回，则该扩展点仅能有一个扩展操作
 */
function FeiLaunch($configname, $launchargs = NULL, $returns = FALSE)
{
	if (isset($GLOBALS['G_Fei']['launch'][$configname]) && is_array($GLOBALS['G_Fei']['launch'][$configname])) {
		foreach ($GLOBALS['G_Fei']['launch'][$configname] as $launch) {
			if (is_array($launch)) {
				$reval = FeiClass($launch[0])->{$launch[1]}($launchargs);
			} else {
				$reval = call_user_func_array($launch, $launchargs);
			}
			if (TRUE == $returns) return $reval;
		}
	}
	return FALSE;
}

/**
 * T
 * 多语言实现，翻译函数
 * @param w    默认语言的词语
 */
function T($w)
{
	$method = $GLOBALS['G_Fei']["lang"][FeiController::getLang()];
	if (!isset($method) || 'default' == $method) {
		return $w;
	} elseif (function_exists($method)) {
		return ($tmp = call_user_func($method, $w)) ? $tmp : $w;
	} elseif (is_array($method)) {
		return ($tmp = FeiClass($method[0])->{$method[1]}($w)) ? $tmp : $w;
	} elseif (file_exists($method)) {
		$dict = require($method);
		return isset($dict[$w]) ? $dict[$w] : $w;
	} else {
		return $w;
	}
}

/**
 * FeiUrl
 * URL模式的构建函数
 * @param controller    控制器名称，默认为配置'default_controller'
 * @param action        动作名称，默认为配置'default_action'
 * @param args          传递的参数，数组形式
 * @param anchor        跳转锚点
 * @param no_Feihtml    是否应用FeiHtml设置，在FALSE时效果与不启用FeiHtml相同。
 */
function FeiUrl($controller = NULL, $action = NULL, $args = NULL, $anchor = NULL, $no_Feihtml = FALSE)
{
	if (TRUE == $GLOBALS['G_Fei']['html']["enabled"] && TRUE != $no_Feihtml) {
		// 当开启HTML生成时，将查找HTML列表获取静态文件名称。
		$realhtml = FeiHtml::getUrl($controller, $action, $args, $anchor);
		if (isset($realhtml[0])) return $realhtml[0];
	}
	$controller = (NULL != $controller) ? $controller : $GLOBALS['G_Fei']["default_controller"];
	$action     = (NULL != $action) ? $action : $GLOBALS['G_Fei']["default_action"];
	// 使用扩展点
	if ($launch = FeiLaunch("function_url", array('controller' => $controller, 'action' => $action, 'args' => $args, 'anchor' => $anchor, 'no_Feihtml' => $no_Feihtml), TRUE)) return $launch;
	if (TRUE == $GLOBALS['G_Fei']['url']["url_path_info"]) { // 使用path_info方式
		$url = $GLOBALS['G_Fei']['url']["url_path_base"] . "/{$controller}/{$action}";
		if (NULL != $args) foreach ($args as $key => $arg) $url .= "/{$key}/{$arg}";
	} else {
		$url = $GLOBALS['G_Fei']['url']["url_path_base"] . "?" . $GLOBALS['G_Fei']["url_controller"] . "={$controller}&";
		$url .= $GLOBALS['G_Fei']["url_action"] . "={$action}";
		if (NULL != $args) foreach ($args as $key => $arg) $url .= "&{$key}={$arg}";
	}
	if (NULL != $anchor) $url .= "#" . $anchor;
	return $url;
}


/**
 * __mkdirs
 * 循环建立目录的辅助函数
 * @param dir     目录路径
 * @param mode    文件权限
 */
function __mkdirs($dir, $mode = 0777)
{
	if (!is_dir($dir)) {
		__mkdirs(dirname($dir), $mode);
		return @mkdir($dir, $mode);
	}
	return TRUE;
}

/**
 * FeiExt
 * 扩展类获取扩展配置的函数
 * @param ext_node_name    扩展配置名
 */
function FeiExt($ext_node_name)
{
	return (empty($GLOBALS['G_Fei']['ext'][$ext_node_name])) ? FALSE : $GLOBALS['G_Fei']['ext'][$ext_node_name];
}

/**
 * FeiAddViewFunction
 * 将函数注册到模板内使用，该函数可以是对象的方法，类的方法或是函数。
 * @param alias                函数在模板内的别名
 * @param callback_function    回调的函数或方法
 */
function FeiAddViewFunction($alias, $callback_function)
{
	return $GLOBALS['G_Fei']["view_registered_functions"][$alias] = $callback_function;
}

/**
 * FeiDB 函数（全称：FeiPHP DataBase），简化数据库操作的函数。
 * FeiDB可以达到简单使用FeiModel子类的快捷方式，在没有FeiModel子类定义的情况下，直接对该表(FeiModel拥有的)操作。
 * FeiDB仅提供FeiModel子类的简便使用方式，如需要强大或丰富的FeiModel子类功能，请仍然对子类进行定义并使用该子类。
 * 开发者可以方便地：
 * 1. 初始化一个FeiModel的子类，即使这个子类的定义不存在
 * 2. 调用该对象的继承FeiModel而来的全部方法
 * @param tbl_name    表全名 或 表名称，开发者可在配置中的db_Feidb_full_tblname设置符合自己使用习惯的方式。
 *                    表全名是默认值，db_Feidb_full_tblname = true，tbl_name值将是（表前缀 + 表名称）
 *                    表名称，db_Feidb_full_tblname = false，这时候框架将使用db配置中的表前缀prefix。
 * @param pk          主键（可选），忽略主键的时候，将获取表第一个字段作为主键（通常都是）
 */
function FeiDB($tbl_name, $pk = NULL)
{
	$modelObj           = FeiClass("FeiModel");
	$modelObj->tbl_name = (TRUE == $GLOBALS['G_Fei']["db_Feidb_full_tblname"]) ? $tbl_name : $GLOBALS['G_Fei']['db']['prefix'] . $tbl_name;
	if (!$pk) { // 主键通过数据库驱动getTable来获取
		@list($pk) = $modelObj->_db->getTable($modelObj->tbl_name);
		$pk = $pk['Field'];
	}
	$modelObj->pk = $pk;
	return $modelObj;
}

/**
 * json_decode/json_encode
 * 兼容在未配置JSON扩展的情况下使用Services_JSON类

 */
if (!function_exists('json_decode')) {
	function json_decode($content, $assoc = FALSE)
	{
		if ($assoc) {
			return FeiClass("Services_JSON", array(16))->decode($content);
		} else {
			return FeiClass("Services_JSON")->decode($content);
		}
	}
}
if (!function_exists('json_encode')) {
	function json_encode($content)
	{
		return FeiClass("Services_JSON")->encode($content);
	}
}

/**
 * FeiConfigReady   快速将用户配置覆盖到框架默认配置
 * @param preconfig    默认配置
 * @param useconfig    用户配置
 */
function FeiConfigReady($preconfig, $useconfig = NULL)
{
	$nowconfig = $preconfig;
	if (is_array($useconfig)) {
		foreach ($useconfig as $key => $val) {
			if (is_array($useconfig[$key])) {
				@$nowconfig[$key] = is_array($nowconfig[$key]) ? FeiConfigReady($nowconfig[$key], $useconfig[$key]) : $useconfig[$key];
			} else {
				@$nowconfig[$key] = $val;
			}
		}
	}
	return $nowconfig;
}