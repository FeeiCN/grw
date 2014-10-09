<?php

define("Fei_Any", "Fei_Any"); // 无权限设置的角色名称

/**
 * 基于组的用户权限判断机制
 * 要使用该权限控制程序，需要在应用程序配置中做以下配置：
 * 有限控制的情况，在配置中使用    'launch' => array( 'router_prefilter' => array( array('FeiAcl','mincheck'), ), )
 * 强制控制的情况，在配置中使用    'launch' => array( 'router_prefilter' => array( array('FeiAcl','maxcheck'), ), )
 */
class FeiAcl
{
	/**
	 * 默认权限检查的处理程序设置，可以是函数名或是数组（array(类名,方法)的形式）
	 */
	public $checker = array('FeiAclModel', 'check');

	/**
	 * 默认提示无权限提示，可以是函数名或是数组（array(类名,方法)的形式）
	 */
	public $prompt = array('FeiAcl', 'def_prompt');

	/**
	 * 构造函数，设置权限检查程序与提示程序
	 */
	public function __construct()
	{
		$params = FeiExt("FeiAcl");
		if (!empty($params["prompt"])) $this->prompt = $params["prompt"];
		if (!empty($params["checker"])) $this->checker = $params["checker"];
	}

	/**
	 * 获取当前会话的用户标识
	 */
	public function get()
	{
		return $_SESSION[$GLOBALS['G_Fei']['sp_app_id'] . "_SpAclSession"];
	}

	/**
	 * 强制控制的检查程序，适用于后台。无权限控制的页面均不能进入
	 */
	public function maxcheck()
	{
		$acl_handle = $this->check();
		if (1 !== $acl_handle) {
			$this->prompt();
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 有限的权限控制，适用于前台。仅在权限表声明禁止的页面起作用，其他无声明页面均可进入
	 */
	public function mincheck()
	{
		$acl_handle = $this->check();
		if (0 === $acl_handle) {
			$this->prompt();
			return FALSE;
		}
		return TRUE;
	}

	/**
	 * 使用程序调度器进行检查等处理
	 */
	private function check()
	{
		GLOBAL $__controller, $__action;
		$checker = $this->checker;
		$name    = $this->get();

		if (is_array($checker)) {
			return FeiClass($checker[0])->{$checker[1]}($name, $__controller, $__action);
		} else {
			return call_user_func_array($checker, array($name, $__controller, $__action));
		}
	}

	/**
	 * 无权限提示跳转
	 */
	public function prompt()
	{
		$prompt = $this->prompt;
		if (is_array($prompt)) {
			return FeiClass($prompt[0])->{$prompt[1]}();
		} else {
			return call_user_func_array($prompt, array());
		}
	}

	/**
	 * 默认的无权限提示跳转
	 */
	public function def_prompt()
	{
		$url = FeiUrl(); // 跳转到首页，在强制权限的情况下，请将该页面设置成可以进入。
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"Access Failed!\");location.href=\"{$url}\";}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}

	/**
	 * 设置当前用户，内部使用SESSION记录
	 * @param acl_name    用户标识：可以是组名或用户名
	 */
	public function set($acl_name)
	{
		$_SESSION[$GLOBALS['G_Fei']['sp_app_id'] . "_SpAclSession"] = $acl_name;
	}
}

/**
 * ACL操作类，通过数据表确定用户权限
 * 表结构：
 * CREATE TABLE acl
 * (
 *    aclid int NOT NULL AUTO_INCREMENT,
 *    name VARCHAR(200) NOT NULL,
 *    controller VARCHAR(50) NOT NULL,
 *    action VARCHAR(50) NOT NULL,
 *    acl_name VARCHAR(50) NOT NULL,
 *    PRIMARY KEY (aclid)
 * ) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci
 */
class FeiAclModel extends FeiModel
{

	public $pk = 'aclid';
	/**
	 * 表名
	 */
	public $table = 'acl';

	/**
	 * 检查对应的权限
	 * 返回1是通过检查，0是不能通过检查（控制器及动作存在但用户标识没有记录）
	 * 返回-1是无该权限控制（即该控制器及动作不存在于权限表中）
	 * @param acl_name      用户标识：可以是组名或是用户名
	 * @param controller    控制器名称
	 * @param action        动作名称
	 */
	public function check($acl_name = Fei_Any, $controller, $action)
	{
		$rows = array('controller' => $controller, 'action' => $action);
		if ($acl = $this->findAll($rows)) {
			foreach ($acl as $v) {
				if ($v["acl_name"] == Fei_Any || $v["acl_name"] == $acl_name) return 1;
			}
			return 0;
		} else {
			return -1;
		}
	}
}