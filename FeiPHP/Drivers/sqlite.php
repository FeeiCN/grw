<?php

/**
 * db_sqlite Sqlite数据库的驱动支持
 */
class db_sqlite
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
		return sqlite_array_query($this->conn, $sql, SQLITE_ASSOC);
	}

	/**
	 * 返回当前插入记录的主键ID
	 */
	public function newinsertid()
	{
		return sqlite_last_insert_rowid($this->conn);
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
		if ($result = sqlite_query($this->conn, $sql, SQLITE_ASSOC, $sqliteerror)) {
			return $result;
		} else {
			spError("{$sql}<br />执行错误: " . $sqliteerror);
		}
	}

	/**
	 * 返回影响行数
	 */
	public function affected_rows()
	{
		return sqlite_changes($this->conn);
	}

	/**
	 * 获取数据表结构
	 * @param tbl_name  表名称
	 */
	public function getTable($tbl_name)
	{
		$cols    = sqlite_fetch_column_types($tbl_name, $this->conn, SQLITE_ASSOC);
		$columns = array();
		foreach ($cols as $column => $type) {
			$columns[] = array('Field' => $column);
		}
		return $columns;
	}

	/**
	 * 构造函数
	 * @param dbConfig  数据库配置
	 */
	public function __construct($dbConfig)
	{
		if (!function_exists('sqlite_open')) spError('PHP环境未安装Sqlite函数库！');
		$linkfunction = (TRUE == $dbConfig['persistent']) ? 'sqlite_popen' : 'sqlite_open';
		if (!$this->conn = $linkfunction($dbConfig['host'], 0666, $sqliteerror)) spError('数据库链接错误/无法找到数据库 : ' . $sqliteerror);
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
		return '\'' . sqlite_escape_string($value) . '\'';
	}

	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		if (TRUE != $GLOBALS['G_SP']['db']['persistent']) @sqlite_close($this->conn);
	}
}

