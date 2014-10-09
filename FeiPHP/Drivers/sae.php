<?php

/**
 * SAE中MySQL数据库的驱动支持
 * SAE是Sina App Engine（新浪应用引擎）的缩写，SAE是一个分布式web应用开发运行的服务平台，
 * 其不仅仅包含创建、部署web应用的简单交互，更涉及一整套大规模分布式服务的解决方案。
 * db_sae 封装了SAE提供的SaeMysql类的驱动操作。
 */
class db_sae
{
	/**
	 * 数据库链接句柄
	 */
	public $conn;
	/**
	 * 执行的SQL语句记录
	 */
	public $arrSql;

	/**
	 * 按SQL语句获取记录结果，返回数组
	 * @param sql  执行的SQL语句
	 */
	public function getArray($sql)
	{
		$this->arrSql[] = $sql;
		$result         = $this->conn->getData($sql);
		if ($this->conn->errno()) spError("{$sql}<br />执行错误: " . $this->conn->error());
		return $result;
	}

	/**
	 * 返回当前插入记录的主键ID
	 */
	public function newinsertid()
	{
		return $this->conn->lastId();
	}

	/**
	 * 格式化带limit的SQL语句
	 */
	public function setlimit($sql, $limit)
	{
		return $sql . " LIMIT {$limit}";
	}

	/**
	 * 执行一个SQL语句
	 * @param sql 需要执行的SQL语句
	 */
	public function exec($sql)
	{
		$this->arrSql[] = $sql;
		$result         = $this->conn->runSql($sql);
		if ($this->conn->errno()) spError("{$sql}<br />执行错误: " . $this->conn->error());
		return $result;
	}

	/**
	 * 返回影响行数
	 */
	public function affected_rows()
	{
		return FALSE; // SAE环境暂时无法获取影响行数
	}

	/**
	 * 获取数据表结构
	 * @param tbl_name  表名称
	 */
	public function getTable($tbl_name)
	{
		return $this->getArray("DESCRIBE {$tbl_name}");
	}

	/**
	 * 构造函数
	 * @param dbConfig  数据库配置
	 */
	public function __construct($dbConfig)
	{
		if (TRUE == SP_DEBUG) sae_set_display_errors(TRUE);
		$this->conn = new SaeMysql();
		if ($this->conn->errno()) spError("数据库链接错误 : " . $this->conn->error());
		$this->conn->setCharset("UTF8");
	}

	/**
	 * 对特殊字符进行过滤
	 * @param value  值
	 */
	public function __val_escape($value, $quotes = FALSE)
	{
		if (is_null($value)) return 'NULL';
		if (is_bool($value)) return $value ? 1 : 0;
		if (is_int($value)) return (int)$value;
		if (is_float($value)) return (float)$value;
		if (@get_magic_quotes_gpc()) $value = stripslashes($value);
		return '\'' . $this->conn->escape($value) . '\'';
	}

	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		@$this->conn->closeDb();
	}

	/**
	 * getConn 取得Sae MySQL对象
	 * 为了更好地使用Sea提供MySQL类，getSeaDB函数将返回Sae MySQL对象供开发者使用
	 */
	public function getConn()
	{
		return $this->conn;
	}
}

