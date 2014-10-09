<?php
//ini_set('display_errors','on');
ini_set("session.cookie_httponly", 1);
session_regenerate_id(TRUE);
session_name('FeiPHP');
define("APP_PATH", dirname(__FILE__));
define("Fei_PATH", APP_PATH . "/FeiPHP");

$FeiConfig = array(
	'controller_path'    => APP_PATH . '/modules/' . basename(__FILE__, ".php"),
	'default_controller' => 'Grw',
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
		'login'      => 'username',
		'password'   => 'password',
		'database'   => 'grw',
		'prefix'     => 'Fei_',
		'presistent' => FALSE,
	),
	'view'               => array(
		'enabled'             => TRUE,
		'config'              => array(
			'template_dir'    => APP_PATH . '/templates',
			'compile_dir'     => APP_PATH . '/cache/templates',
			'cache_dir'       => APP_PATH . '/cache/templates',
			'left_delimiter'  => '{Fei:',
			'right_delimiter' => '}',
			'auto_literal'    => TRUE,
		),
		'debuging'            => TRUE,
		'engine_name'         => 'Smarty',
		'engine_path'         => Fei_PATH . '/Drivers/Smarty/Smarty.class.php',
		'auto_ob_start'       => TRUE,
		'auto_display'        => TRUE,
		'auto_display_sep'    => '/',
		'auto_display_suffix' => '.html',
	),
	'launch'             => array(
		'router_prefilter' => array(
			array('FeiAcl', 'maxcheck') // 开启有限的权限控制
			// array('FeiAcl','maxcheck') // 开启强制的权限控制
		)
	),
	'ext'                => array( // 扩展设置
		'FeiAcl'   => array( // acl扩展设置
			// 在acl中，设置无权限执行将lib_user类的acljump函数
			'prompt' => array("model_user", "acljump"),
		),
		'FeiEmail' => array( //邮件扩展的基本设置
			'debug'     => TRUE, //调试模式
			'host_name' => 'localhost', //邮件主机名
			'smtp_host' => 'smtp.xxx.com', //SMTP服务器
			'smtp_port' => '25', //SMTP端口
			'auth'      => TRUE, //身份验证
			'from'      => '', //发件邮箱
			'user'      => '', //用户名
			'pass'      => '', //密码
			'log_file'  => '', //日志文件
			'time_out'  => 30, //超时时间
		)
	)
);
require(Fei_PATH . "/FeiPHP.php");
import(APP_PATH . "/modules/admin/Grw.php");
import(Fei_PATH . "/Extensions/ChromePhp.php");
import(APP_PATH . "/lib/TemplateParse.php", FALSE, TRUE);
import(APP_PATH . "/lib/glob.function.php");
FeiRun();
