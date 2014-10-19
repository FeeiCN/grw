<?php
/**
 * 前台页面入口
 */
ini_set('display_errors','off');
define("APP_PATH", dirname(__FILE__));
define("Fei_PATH", APP_PATH . "/FeiPHP");
session_start();
if ($_SERVER['HTTP_HOST'] == 'www.grw.name') {
	$tem_path           = APP_PATH . '/themes/web3';
	$_SESSION['In_www'] = TRUE;
} else {
	$tem_path = APP_PATH . '/themes/grw';
}
$FeiConfig = array(
	'controller_path'    => APP_PATH . '/modules/content',
	'default_controller' => 'content',
	'model_path'         => APP_PATH . '/model', // 定义model类的路径
	'include_path'       => array(
		APP_PATH . '/lib',
	), // 用户程序扩展类载入路径
	'lang'               => array(
		'zh-cn' => APP_PATH . '/languages/zh-cn.php'
	),
	'db'                 => array(
		'driver'     => 'mysql',
		'host'       => 'localhost',
		'port'       => 3306,
		'login'      => 'root',
		'password'   => '',
		'database'   => 'grw',
		'prefix'     => 'Fei_',
		'presistent' => FALSE,
	),
	'view'               => array(
		'enabled'       => TRUE,
		'config'        => array(
			'template_dir'    => $tem_path,
			'compile_dir'     => APP_PATH . '/cache/themes',
			'cache_dir'       => APP_PATH . '/cache/themes',
			'left_delimiter'  => '{Fei:',
			'right_delimiter' => '}',
			'auto_literal'    => TRUE,
		),
		'debuging'      => TRUE,
		'engine_name'   => 'Smarty',
		'engine_path'   => Fei_PATH . '/Drivers/Smarty/Smarty.class.php',
		'auto_ob_start' => TRUE,
		//'auto_display' => TRUE,
		//'auto_display_sep' => '/',
		//'auto_display_suffix' => '.html',
	),
	'ext'                => array(),

);
require(Fei_PATH . "/FeiPHP.php");
import(APP_PATH . "/lib/TemplateParse.php", FALSE, TRUE);
FeiRun();
