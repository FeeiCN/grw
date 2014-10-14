<?php

define('Fei_VERSION', '3.1.89'); // 当前框架版本
if (substr(PHP_VERSION, 0, 1) != '5') exit("FeiPHP框架环境要求PHP5！");
/**
 * FeiCore
 * FeiPHP应用框架的系统执行程序
 */

// 定义系统路径
if (!defined('Fei_PATH')) define('Fei_PATH', dirname(__FILE__) . '/FeiPHP');
if (!defined('APP_PATH')) define('APP_PATH', dirname(__FILE__) . '/app');

// 载入核心函数库
require(Fei_PATH . "/FeiFunctions.php");

// 载入配置文件
$GLOBALS['G_Fei'] = FeiConfigReady(require(Fei_PATH . "/FeiConfig.php"), $FeiConfig);

// 根据配置文件进行一些全局变量的定义
if ('debug' == $GLOBALS['G_Fei']['mode']) {
	define("Fei_DEBUG", TRUE); // 当前正在调试模式下
} else {
	define("Fei_DEBUG", FALSE); // 当前正在部署模式下
}
// 如果是调试模式，打开警告输出
if (Fei_DEBUG) {
	if (substr(PHP_VERSION, 0, 3) == "5.3") {
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING & ~E_DEPRECATED);
	} else {
		error_reporting(E_ALL & ~E_NOTICE & ~E_WARNING);
	}
} else {
	error_reporting(0);
}
@set_magic_quotes_runtime(0);

// 自动开启SESSION
if ($GLOBALS['G_Fei']['auto_session']) @session_start();

// 载入核心MVC架构文件
import($GLOBALS['G_Fei']["Fei_core_path"] . "/FeiController.php", FALSE, TRUE);
import($GLOBALS['G_Fei']["Fei_core_path"] . "/FeiModel.php", FALSE, TRUE);
import($GLOBALS['G_Fei']["Fei_core_path"] . "/FeiView.php", FALSE, TRUE);

// 当在二级目录中使用FeiPHP框架时，自动获取当前访问的文件名
if ('' == $GLOBALS['G_Fei']['url']["url_path_base"]) {
	if (basename($_SERVER['SCRIPT_NAME']) === basename($_SERVER['SCRIPT_FILENAME']))
		$GLOBALS['G_Fei']['url']["url_path_base"] = $_SERVER['SCRIPT_NAME'];
	elseif (basename($_SERVER['PHP_SELF']) === basename($_SERVER['SCRIPT_FILENAME']))
		$GLOBALS['G_Fei']['url']["url_path_base"] = $_SERVER['PHP_SELF'];
	elseif (isset($_SERVER['ORIG_SCRIPT_NAME']) && basename($_SERVER['ORIG_SCRIPT_NAME']) === basename($_SERVER['SCRIPT_FILENAME']))
		$GLOBALS['G_Fei']['url']["url_path_base"] = $_SERVER['ORIG_SCRIPT_NAME'];
}

// 在使用PATH_INFO的情况下，对路由进行预处理
if (TRUE == $GLOBALS['G_Fei']['url']["url_path_info"] && !empty($_SERVER['PATH_INFO'])) {
	$url_args = explode("/", $_SERVER['PATH_INFO']);
	$url_sort = array();
	for ($u = 1; $u < count($url_args); $u++) {
		if ($u == 1) $url_sort[$GLOBALS['G_Fei']["url_controller"]] = $url_args[$u];
		elseif ($u == 2) $url_sort[$GLOBALS['G_Fei']["url_action"]] = $url_args[$u];
		else {
			$url_sort[$url_args[$u]] = isset($url_args[$u + 1]) ? $url_args[$u + 1] : "";
			$u += 1;
		}
	}
	if ("POST" == strtoupper($_SERVER['REQUEST_METHOD'])) {
		$_REQUEST = $_POST = $_POST + $url_sort;
	} else {
		$_REQUEST = $_GET = $_GET + $url_sort;
	}
}

// 构造执行路由
$__controller = isset($_REQUEST[$GLOBALS['G_Fei']["url_controller"]]) ?
	$_REQUEST[$GLOBALS['G_Fei']["url_controller"]] :
	$GLOBALS['G_Fei']["default_controller"];
$__action     = isset($_REQUEST[$GLOBALS['G_Fei']["url_action"]]) ?
	$_REQUEST[$GLOBALS['G_Fei']["url_action"]] :
	$GLOBALS['G_Fei']["default_action"];

// 自动执行用户代码
if (TRUE == $GLOBALS['G_Fei']['auto_Fei_run']) FeiRun();