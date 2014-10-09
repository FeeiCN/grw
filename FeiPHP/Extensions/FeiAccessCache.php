<?php

/**
 * FeiAccessCache 类，以扩展形式支持FeiAccess函数拥有更多的缓存方式的扩展。
 * 目前FeiAccessCache支持的缓存驱动类型如下：
 * Xcache（驱动名称：xcache)
 * Memcache (memcache)
 * APC (apc)
 * eAccelerator (eaccelerator)
 * SAE的memcache (saememcache)
 * 使用数据库作为缓存 (db)
 * 请注意：Memcache、db 驱动类有其特殊的设置，请参考类注释。
 * 应用程序配置中需要使用到路由扩展点以及FeiAccess扩展点
 * 'launch' => array(
 *    'function_access' => array(
 *            array("FeiAccessCache", "xcache"), // 第二个参数为缓存驱动类型的名称
 *        ),
 *),
 * 本扩展要求SpeedPHP框架2.5版本以上，以支持对FeiAccess函数的扩展程序。
 */
if (Fei_VERSION < 2.5) FeiError('FeiAccessCache扩展要求SpeedPHP框架版本2.5以上。');

class FeiAccessCache
{
	/**
	 * 魔术函数  通过函数名来调用不同的缓存驱动类
	 */
	public function __call($name, $args)
	{
		$driverClass = 'access_driver_' . $name;
		if (!class_exists($driverClass)) FeiError('FeiAccess无法找到名为{$name}缓存驱动程序，请检查!');
		extract(array_pop($args));
		if ('w' == $method) { // 写数据
			$life_time = (-1 == $life_time) ? '300000000' : $life_time;
			return FeiClass($driverClass)->set($name, serialize($value), $life_time);
		} elseif ('c' == $method) { // 清除数据
			return FeiClass($driverClass)->del($name);
		} else { // 读数据
			return unserialize(FeiClass($driverClass)->get($name));
		}
	}
}

/**
 * access_driver_memcache  memcache缓存驱动类
 * memcache服务器的默认设置是 localhost:11211，如果您的设置与之不相同，请做以下配置：
 * 'ext' => array(
 *        'FeiAccessCache' => array(
 *            'memcache_host' => '123.456.789.10', // memcache服务器地址
 *            'memcache_port' => '1111', // memcache服务器端口
 *        ),
 * ),
 */
class access_driver_memcache
{
	public $mmc = NULL;

	public function __construct()
	{
		if (!function_exists('memcache_connect')) FeiError('PHP环境未安装Memcache函数库！');
		$params        = spExt('FeiAccessCache');
		$memcache_host = (isset($params['memcache_host'])) ? $params['memcache_host'] : 'localhost';
		$memcache_port = (isset($params['memcache_port'])) ? $params['memcache_port'] : '11211';
		$this->mmc     = memcache_connect($memcache_host, $memcache_port);
	}

	public function get($name)
	{
		return memcache_get($this->mmc, $name);
	}

	public function set($name, $value, $life_time)
	{
		return memcache_set($this->mmc, $name, $value, 0, $life_time);
	}

	public function del($name)
	{
		return memcache_delete($this->mmc, $name);
	}
}

/**
 * access_driver_saememcache  SAE的memcache缓存驱动类
 */
class access_driver_saememcache
{
	public $mmc = NULL;

	public function __construct()
	{
		if (!$this->mmc = memcache_init()) FeiError("SAE的memcache初始化失败！");
	}

	public function get($name)
	{
		return memcache_get($this->mmc, $name);
	}

	public function set($name, $value, $life_time)
	{
		return memcache_set($this->mmc, $name, $value, 0, $life_time);
	}

	public function del($name)
	{
		return memcache_delete($this->mmc, $name);
	}
}

/**
 * access_driver_apc  APC缓存驱动类
 */
class access_driver_apc
{
	public function __construct()
	{
		if (!function_exists('apc_store')) FeiError('PHP环境未安装APC函数库！');
	}

	public function get($name)
	{
		return apc_fetch($name);
	}

	public function set($name, $value, $life_time)
	{
		return apc_store($name, $value, $life_time);
	}

	public function del($name)
	{
		return apc_delete($name);
	}
}

/**
 * access_driver_eaccelerator  eAccelerator缓存驱动类
 */
class access_driver_eaccelerator
{
	public function __construct()
	{
		if (!function_exists('eaccelerator_put')) FeiError('PHP环境未安装eAccelerator函数库！');
	}

	public function get($name)
	{
		return eaccelerator_get($name);
	}

	public function set($name, $value, $life_time)
	{
		return eaccelerator_put($name, $value, $life_time);
	}

	public function del($name)
	{
		return eaccelerator_rm($name);
	}
}

/**
 * access_driver_xcache  Xcache缓存驱动类
 */
class access_driver_xcache
{
	public function __construct()
	{
		if (!function_exists('xcache_set')) FeiError('PHP环境未安装Xcache函数库！');
	}

	public function get($name)
	{
		return xcache_get($name);
	}

	public function set($name, $value, $life_time)
	{
		return xcache_set($name, $value, $life_time);
	}

	public function del($name)
	{
		return xcache_unset($name);
	}
}

/**
 * access_driver_db  数据库缓存驱动类
 * access_driver_db可以让开发者使用数据库本身作为缓存驱动。
 * 在使用 access_driver_db 之前，务必建立对应的 access_cache 数据表
 * 生成表语句：
 * CREATE TABLE `access_cache` (
 *   `cacheid` bigint(20) NOT NULL AUTO_INCREMENT,
 *   `cachename` varchar(100) NOT NULL,
 *   `cachevalue` text,
 *   PRIMARY KEY (`cacheid`)
 * ) ENGINE=MyISAM DEFAULT CHARSET=utf8;

 */
class access_driver_db extends spModel
{
	public $pk = 'cacheid';
	public $table = 'access_cache';

	public function get($name)
	{
		if (!$result = array_pop($this->find(array('cachename' => $name), 'cacheid DESC', 'cachevalue'))) return FALSE;
		if (substr($result, 0, 10) < time()) {
			$this->del($name);
			return FALSE;
		}
		return unserialize(substr($result, 10));
	}

	public function set($name, $value, $life_time)
	{
		$value = (time() + $life_time) . serialize($value);
		if (FALSE !== $this->find(array('cachename' => $name), 'cacheid DESC', 'cachevalue')) {
			return $this->updateField(array('cachename' => $name), 'cachevalue', $value);
		} else {
			return $this->create(array('cachename' => $name, 'cachevalue' => $value));
		}
	}

	public function del($name)
	{
		return $this->delete(array('cachename' => $name));
	}
}