<?php
/**
 * FeiUrlRewrite 类，以扩展形式支持SpeedPHP框架URL_REWRITE的扩展。
 * 该扩展的使用，首先要确定服务器开启URL_REWRITE功能，并且在.htaccess中已经有以下的内容
 * .htaccess是针对当前应用程序的
 * <IfModule mod_rewrite.c>
 * RewriteEngine On
 * RewriteCond %{REQUEST_FILENAME} !-f
 * RewriteCond %{REQUEST_FILENAME} !-d
 * RewriteRule ^(.*)$ index.php?$1 [L]
 * </IfModule>
 * 本扩展要求SpeedPHP框架2.5版本以上，以支持对spUrl函数的扩展程序。
 * 应用程序配置中需要使用到路由扩展点以及spUrl扩展点
 * 'launch' => array(
 *        'router_prefilter' => array(
 *            array('FeiUrlRewrite', 'setReWrite'),
 *        ),
 *    'function_url' => array(
 *            array("FeiUrlRewrite", "getReWrite"),
 *        ),
 *),
 * 对FeiUrlRewrite的配置
 * 'ext' => array(
 *        'FeiUrlRewrite' => array(
 *            'suffix' => '.html', // 生成地址的结尾符，网址后缀
 *            'sep' => '/', // 网址参数分隔符，建议是“-_/”之一
 *            'map' => array( // 网址映射，比如 'search' => 'main@search'，
 *                            // 将使得 http://www.example.com/search.html 转向控制器main/动作serach执行
 *                            // 特例 '@' => 'main@no' 如果映射是@，将使得符合以下条件的网址转向到 控制器main/动作no执行：
 *                            // 1. 在map中无法找到其他映射，2. 网址第一个参数并非控制器名称。
 *            ),
 *            'args' => array( // 网址映射附加的隐藏参数，如果针对某个网址映射设置了隐藏参数，则在网址中仅会存在参数值，而参数名称被隐藏。
 *                             // 比如 'search' => array('q','page'), 那么生成的网址将会是：
 *                             // http://www.example.com/search-thekey-2.html
 *                             // 配合map映射'search' => 'main@search'，这个网址将会执行 控制器main/动作serach，
 *                             // 而参数q将等于thekey，参数page将等于2
 *            ),
 *        ),
 * ),

 */
if (Fei_VERSION < 2.5) spError('FeiUrlRewrite扩展要求SpeedPHP框架版本2.5以上。');

class FeiUrlRewrite
{
	var $params = array(
		// 'hide_default' => true, // 隐藏默认的main/index名称，已无效
		// 'args_path_info' => false, // 地址参数是否使用path_info模式，已无效。全为非path_info的模式
		'suffix' => '.html',
		'sep'    => '-',
		'map'    => array(),
		'args'   => array(),
	);

	/**
	 * 构造函数，处理配置
	 */
	public function __construct()
	{
		$params = FeiExt('FeiUrlRewrite');
		if (is_array($params)) $this->params = array_merge($this->params, $params);
	}

	/**
	 * 在控制器/动作执行前，对路由进行改装，使其可以解析URL_WRITE的地址
	 */
	public function setReWrite()
	{
		GLOBAL $__controller, $__action;
		if (isset($_SERVER['HTTP_X_REWRITE_URL'])) $_SERVER['REQUEST_URI'] = $_SERVER['HTTP_X_REWRITE_URL'];
		// $request = ltrim(strtolower(substr($_SERVER["REQUEST_URI"], strlen(dirname($GLOBALS['G_Fei']['url']['url_path_base'])))),"\/\\");
		$request = ltrim(substr($_SERVER["REQUEST_URI"], strlen(dirname($GLOBALS['G_Fei']['url']['url_path_base']))), "\/\\");
		if ('?' == substr($request, 0, 1) or 'index.php?' == substr($request, 0, 10)) return;
		if (empty($request) or 'index.php' == $request) {
			$__controller = $GLOBALS['G_Fei']['default_controller'];
			$__action     = $GLOBALS['G_Fei']['default_action'];
			return;
		}
		$request      = explode((('' == $this->params['suffix']) ? '?' : $this->params['suffix']), $request, 2);
		$uri          = array('first' => array_shift($request), 'last' => ltrim(implode($request), '?'));
		$request      = explode($this->params['sep'], $uri['first']);
		$uri['first'] = array('pattern' => array_shift($request), 'args' => $request);

		if (array_key_exists($uri['first']['pattern'], $this->params['map'])) {
			@list($__controller, $__action) = explode('@', $this->params['map'][$uri['first']['pattern']]);
			if (!empty($this->params['args'][$uri['first']['pattern']])) foreach ($this->params['args'][$uri['first']['pattern']] as $v) spClass("spArgs")->set($v, array_shift($uri['first']['args']));
		} elseif (isset($this->params['map']['@']) && !in_array($uri['first']['pattern'] . '.php', array_map('strtolower', scandir($GLOBALS['G_Fei']['controller_path'])))) {
			@list($__controller, $__action) = explode('@', $this->params['map']['@']);
			if (!empty($this->params['args']['@'])) {
				$uri['first']['args'] = array_merge(array($uri['first']['pattern']), $uri['first']['args']);
				foreach ($this->params['args']['@'] as $v) spClass("spArgs")->set($v, array_shift($uri['first']['args']));
			}
		} else {
			$__controller = $uri['first']['pattern'];
			$__action     = array_shift($uri['first']['args']);
			if (empty($__action)) $__action = $GLOBALS['G_Fei']['default_action'];
		}
		if (!empty($uri['first']['args'])) for ($u = 0; $u < count($uri['first']['args']); $u++) {
			spClass("spArgs")->set($uri['first']['args'][$u], isset($uri['first']['args'][$u + 1]) ? $uri['first']['args'][$u + 1] : "");
			$u += 1;
		}
		if (!empty($uri['last'])) {
			$uri['last'] = explode('&', $uri['last']);
			foreach ($uri['last'] as $val) {
				@list($k, $v) = explode('=', $val);
				if (!empty($k)) spClass("spArgs")->set($k, isset($v) ? $v : "");
			}
		}
	}


	/**
	 * 在构造spUrl地址时，对地址进行URL_WRITE的改写
	 * @param urlargs    spUrl的参数
	 */
	public function getReWrite($urlargs = array())
	{
		$uri = trim(dirname($GLOBALS['G_Fei']['url']["url_path_base"]), "\/\\");
		if (empty($uri)) {
			$uri = '/';
		} else {
			$uri = '/' . $uri . '/';
		}
		if ($GLOBALS['G_Fei']["default_controller"] == $urlargs['controller'] && $GLOBALS['G_Fei']["default_action"] == $urlargs['action'] && empty($urlargs['args'])) {
			return $uri . ((NULL != $urlargs['anchor']) ? "#{$anchor}" : '');
		} elseif ($k = array_search(strtolower($urlargs['controller'] . '@' . $urlargs['action']), array_map('strtolower', $this->params['map']))) {
			$uri .= ('@' == $k) ? '' : $k;
			$isfirstmark = ('@' == $k);
			if (!empty($this->params['args'][$k]) && !empty($urlargs['args'])) {
				foreach ($this->params['args'][$k] as $defarg) {
					if ($isfirstmark) {
						$uri .= isset($urlargs['args'][$defarg]) ? $urlargs['args'][$defarg] : '';
						$isfirstmark = 0;
					} else {
						$uri .= isset($urlargs['args'][$defarg]) ? $this->params['sep'] . $urlargs['args'][$defarg] : $this->params['sep'];
					}
					unset($urlargs['args'][$defarg]);
				}
			}
		} else {
			$uri .= $urlargs['controller'];
			if (!empty($urlargs['args']) || (!empty($urlargs['action']) && $urlargs['action'] != $GLOBALS['G_Fei']["default_action"])) $uri .= $this->params['sep'] . $urlargs['action'];
		}
		if (!empty($urlargs['args'])) {
			foreach ($urlargs['args'] as $k => $v) $uri .= $this->params['sep'] . $k . $this->params['sep'] . $v;
		} else {
			$uri = rtrim($uri, $this->params['sep']);
		}
		return $uri . $this->params['suffix'] . ((NULL != $urlargs['anchor']) ? "#{$anchor}" : '');
	}
}