<?php

/**
 * db_mysql MySQL数据库的驱动支持
 */
class db_mysql
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
		if (!$result = $this->exec($sql)) return array();
		if (!mysql_num_rows($result)) return array();
		$rows = array();
		while ($rows[] = mysql_fetch_array($result, MYSQL_ASSOC)) {
		}
		mysql_free_result($result);
		array_pop($rows);
		return $rows;
	}

	/**
	 * 返回当前插入记录的主键ID
	 */
	public function newinsertid()
	{
		return mysql_insert_id($this->conn);
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
		if ($result = mysql_query($sql, $this->conn)) {
			return $result;
		} else {
			FeiError("{$sql}<br />执行错误: " . mysql_error());
		}
	}

	/**
	 * 返回影响行数
	 */
	public function affected_rows()
	{
		return mysql_affected_rows($this->conn);
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
		$linkfunction = (TRUE == $dbConfig['persistent']) ? 'mysql_pconnect' : 'mysql_connect';
		$this->conn = $linkfunction($dbConfig['host'] . ":" . $dbConfig['port'], $dbConfig['login'], $dbConfig['password']) or FeiError("数据库链接错误 : " . mysql_error());
		mysql_select_db($dbConfig['database'], $this->conn) or FeiError("无法找到数据库，请确认数据库名称正确！");
		$this->exec("SET NAMES UTF8");
	}

	/**
	 * 对特殊字符进行过滤
	 * @param value  值
	 */
	public function __val_escape($value)
	{
		if (is_null($value)) return 'NULL';
		if (is_bool($value)) return $value ? 1 : 0;
		if (is_int($value)) return (int)$value;
		if (is_float($value)) return (float)$value;
		if (@get_magic_quotes_gpc()) $value = stripslashes($value);
		return '\'' . mysql_real_escape_string($value, $this->conn) . '\'';
	}

	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		if (TRUE != $GLOBALS['G_Fei']['db']['persistent']) @mysql_close($this->conn);
	}
}

