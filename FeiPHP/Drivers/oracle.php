<?php

/**
 * db_oracle Oracle数据库的驱动支持
 */
class db_oracle
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
	 * exec执行影响行数
	 */
	private $num_rows;

	/**
	 * 按SQL语句获取记录结果，返回数组
	 * @param sql  执行的SQL语句
	 */
	public function getArray($sql)
	{
		$result = $this->exec($sql);
		oci_fetch_all($result, $res, NULL, NULL, OCI_FETCHSTATEMENT_BY_ROW);
		oci_free_statement($result);
		return $res;
	}

	/**
	 * 返回当前插入记录的主键ID
	 */
	public function newinsertid()
	{
		return FALSE; // 使用spModel的create来进行查找最后插入ID
	}

	/**
	 * 格式化带limit的SQL语句
	 */
	public function setlimit($sql, $limit)
	{
		$limitarr = explode(',', str_replace(' ', '', $limit));
		$total    = (isset($limitarr[1])) ? ($limitarr[1] + $limitarr[0]) : $limitarr[0];
		$start    = (isset($limitarr[1])) ? $limitarr[0] : 0;
		return "SELECT * FROM ( SELECT SPTMP_LIMIT_TBLNAME.*, ROWNUM SPTMP_LIMIT_ROWNUM FROM ({$sql}) SPTMP_LIMIT_TBLNAME WHERE ROWNUM <= {$total} )WHERE SPTMP_LIMIT_ROWNUM > {$start}";
	}

	/**
	 * 执行一个SQL语句
	 * @param sql 需要执行的SQL语句
	 */
	public function exec($sql)
	{
		$this->arrSql[] = $sql;
		$result         = oci_parse($this->conn, $sql);
		if (!oci_execute($result)) {
			$e = oci_error($result);
			spError("{$sql}<br />执行错误: " . strip_tags($e['message']));
		}
		$this->num_rows = oci_num_rows($result);
		return $result;
	}


	/**
	 * 返回影响行数
	 */
	public function affected_rows()
	{
		return $this->num_rows;
	}

	/**
	 * 获取数据表结构
	 * @param tbl_name  表名称
	 */
	public function getTable($tbl_name)
	{
		$tbl_name  = strtoupper($tbl_name);
		$upcaseres = $this->getArray("SELECT COLUMN_NAME AS FIELD FROM USER_TAB_COLUMNS WHERE TABLE_NAME = '{$tbl_name}'");
		foreach ($upcaseres as $k => $v) $upcaseres[$k] = array('Field' => $v['FIELD']);
		return $upcaseres;
	}

	/**
	 * 构造函数
	 * @param dbConfig  数据库配置
	 */
	public function __construct($dbConfig)
	{
		if (!function_exists('oci_connect')) spError('PHP环境未安装ORACLE函数库！');
		$linkfunction = (TRUE == $dbConfig['persistent']) ? 'oci_pconnect' : 'oci_connect';
		if (!$this->conn = $linkfunction($dbConfig['login'], $dbConfig['password'], $dbConfig['host'], 'AL32UTF8')) {
			$e = oci_error();
			spError('数据库链接错误 : ' . strip_tags($e['message']));
		}
		$this->exec('ALTER SESSION SET NLS_DATE_FORMAT = \'yyyy-mm-dd hh24:mi:ss\'');
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
		$search  = array("\\", "\0", "\n", "\r", "\x1a", "'", '"');
		$replace = array("\\\\", "\\0", "\\n", "\\r", "\Z", "\'", '\"');
		return '\'' . str_replace($search, $replace, $value) . '\'';
	}

	/**
	 * 析构函数
	 */
	public function __destruct()
	{
		if (TRUE != $GLOBALS['G_SP']['db']['persistent']) @oci_close($this->conn);
	}
}
