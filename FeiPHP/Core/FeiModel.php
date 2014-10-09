<?php

/**
 * FeiModel 系统模型类，所有模型类的父类 应用程序中的每个模型类都应继承于FeiModel。
 */
class FeiModel
{
	/**
	 * 供检验值的规则与返回信息
	 */
	public $verifier = NULL;

	/**
	 * 增加的自定义验证函数
	 */
	public $addrules = array();
	/**
	 * 表主键
	 */
	public $pk;
	/**
	 * 表名称
	 */
	public $table;

	/**
	 * 关联描述
	 */
	public $linker = NULL;

	/**
	 * 表全名
	 */
	public $tbl_name = NULL;

	/**
	 * 数据驱动程序
	 */
	public $_db;

	/**
	 * 构造函数
	 */
	public function __construct()
	{
		if (NULL == $this->tbl_name) $this->tbl_name = $GLOBALS['G_Fei']['db']['prefix'] . $this->table;
		if ('' == $GLOBALS['G_Fei']['db_driver_path']) {
			$GLOBALS['G_Fei']['db_driver_path'] = $GLOBALS['G_Fei']['Fei_drivers_path'] . '/' . $GLOBALS['G_Fei']['db']['driver'] . '.php';
		}
		$this->_db = FeiClass('db_' . $GLOBALS['G_Fei']['db']['driver'], array(0 => $GLOBALS['G_Fei']['db']), $GLOBALS['G_Fei']['db_driver_path']);
	}

	/**
	 * 从数据表中查找一条记录
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 *                      请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param sort          排序，等同于“ORDER BY ”
	 * @param fields        返回的字段范围，默认为返回全部字段的值
	 */
	public function find($conditions = NULL, $sort = NULL, $fields = NULL)
	{
		if ($record = $this->findAll($conditions, $sort, $fields, 1)) {
			return array_pop($record);
		} else {
			return FALSE;
		}
	}

	/**
	 * 从数据表中查找记录
	 * @param conditions    查找条件，数组array("字段名"=>"查找值")或字符串，
	 *                      请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 * @param sort          排序，等同于“ORDER BY ”
	 * @param fields        返回的字段范围，默认为返回全部字段的值
	 * @param limit         返回的结果数量限制，等同于“LIMIT ”，如$limit = " 3, 5"，即是从第3条记录（从0开始计算）开始获取，共获取5条记录
	 *                      如果limit值只有一个数字，则是指代从0条记录开始。
	 */
	public function findAll($conditions = NULL, $sort = NULL, $fields = NULL, $limit = NULL)
	{
		$where  = "";
		$fields = empty($fields) ? "*" : $fields;
		if (is_array($conditions)) {
			$join = array();
			foreach ($conditions as $key => $condition) {
				$condition = $this->escape($condition);
				$join[]    = "{$key} = {$condition}";
			}
			$where = "WHERE " . join(" AND ", $join);
		} else {
			if (NULL != $conditions) $where = "WHERE " . $conditions;
		}
		if (NULL != $sort) {
			$sort = "ORDER BY {$sort}";
		} else {
			$sort = "ORDER BY {$this->pk}";
		}
		$sql = "SELECT {$fields} FROM {$this->tbl_name} {$where} {$sort}";
		if (NULL != $limit) $sql = $this->_db->setlimit($sql, $limit);
		return $this->_db->getArray($sql);
	}

	/**
	 * 过滤转义字符
	 * @param value 需要进行过滤的值
	 */
	public function escape($value)
	{
		return $this->_db->__val_escape($value);
	}

	// __val_escape是val的别名，向前兼容
	public function __val_escape($value)
	{
		return $this->escape($value);
	}

	/**
	 * 在数据表中新增一行数据
	 * @param row 数组形式，数组的键是数据表中的字段名，键对应的值是需要新增的数据。
	 */
	public function create($row)
	{
		if (!is_array($row)) return FALSE;
		$row = $this->__prepera_format($row);
		if (empty($row)) return FALSE;
		foreach ($row as $key => $value) {
			$cols[] = $key;
			$vals[] = $this->escape($value);
		}
		$col = join(',', $cols);
		$val = join(',', $vals);

		$sql = "INSERT INTO {$this->tbl_name} ({$col}) VALUES ({$val})";
		if (FALSE != $this->_db->exec($sql)) { // 获取当前新增的ID
			if ($newinserid = $this->_db->newinsertid()) {
				return $newinserid;
			} else {
				return array_pop($this->find($row, "{$this->pk} DESC", $this->pk));
			}
		}
		return FALSE;
	}

	/**
	 * 在数据表中新增多条记录
	 * @param rows 数组形式，每项均为create的$row的一个数组
	 */
	public function createAll($rows)
	{
		foreach ($rows as $row) $this->create($row);
	}

	/**
	 * 按条件删除记录
	 * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 */
	public function delete($conditions)
	{
		$where = "";
		if (is_array($conditions)) {
			$join = array();
			foreach ($conditions as $key => $condition) {
				$condition = $this->escape($condition);
				$join[]    = "{$key} = {$condition}";
			}
			$where = "WHERE ( " . join(" AND ", $join) . ")";
		} else {
			if (NULL != $conditions) $where = "WHERE ( " . $conditions . ")";
		}
		$sql = "DELETE FROM {$this->tbl_name} {$where}";
		return $this->_db->exec($sql);
	}

	/**
	 * 按字段值查找一条记录
	 * @param field 字符串，对应数据表中的字段名
	 * @param value 字符串，对应的值
	 */
	public function findBy($field, $value)
	{
		return $this->find(array($field => $value));
	}

	/**
	 * 按字段值修改一条记录
	 * @param conditions 数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param field      字符串，对应数据表中的需要修改的字段名
	 * @param value      字符串，新值
	 */
	public function updateField($conditions, $field, $value)
	{
		return $this->update($conditions, array($field => $value));
	}

	/**
	 * 使用SQL语句进行查找操作，等于进行find，findAll等操作
	 * @param sql 字符串，需要进行查找的SQL语句
	 */
	public function findSql($sql)
	{
		return $this->_db->getArray($sql);
	}

	/**
	 * 执行SQL语句，相等于执行新增，修改，删除等操作。
	 * @param sql 字符串，需要执行的SQL语句
	 */
	public function runSql($sql)
	{
		return $this->_db->exec($sql);
	}

	// query是runSql的别名，向前兼容
	public function query($sql)
	{
		return $this->runSql($sql);
	}

	/**
	 * 返回最后执行的SQL语句供分析
	 */
	public function dumpSql()
	{
		return end($this->_db->arrSql);
	}

	/**
	 * 返回上次执行update,create,delete,exec的影响行数
	 */
	public function affectedRows()
	{
		return $this->_db->affected_rows();
	}

	/**
	 * 计算符合条件的记录数量
	 * @param conditions 查找条件，数组array("字段名"=>"查找值")或字符串，
	 *                   请注意在使用字符串时将需要开发者自行使用escape来对输入值进行过滤
	 */
	public function findCount($conditions = NULL)
	{
		$where = "";
		if (is_array($conditions)) {
			$join = array();
			foreach ($conditions as $key => $condition) {
				$condition = $this->escape($condition);
				$join[]    = "{$key} = {$condition}";
			}
			$where = "WHERE " . join(" AND ", $join);
		} else {
			if (NULL != $conditions) $where = "WHERE " . $conditions;
		}
		$sql    = "SELECT COUNT({$this->pk}) AS Fei_COUNTER FROM {$this->tbl_name} {$where}";
		$result = $this->_db->getArray($sql);
		return $result[0]['Fei_COUNTER'];
	}

	/**
	 * 魔术函数，执行模型扩展类的自动加载及使用
	 */
	public function __call($name, $args)
	{
		if (in_array($name, $GLOBALS['G_Fei']["auto_load_model"])) {
			return FeiClass($name)->__input($this, $args);
		} elseif (!method_exists($this, $name)) {
			FeiError("方法 {$name} 未定义");
		}
	}

	/**
	 * 修改数据，该函数将根据参数中设置的条件而更新表中数据
	 * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param row           数组形式，修改的数据，
	 *                      此参数的格式用法与create的$row是相同的。在符合条件的记录中，将对$row设置的字段的数据进行修改。
	 */
	public function update($conditions, $row)
	{
		$where = "";
		$row   = $this->__prepera_format($row);
		if (empty($row)) return FALSE;
		if (is_array($conditions)) {
			$join = array();
			foreach ($conditions as $key => $condition) {
				$condition = $this->escape($condition);
				$join[]    = "{$key} = {$condition}";
			}
			$where = "WHERE " . join(" AND ", $join);
		} else {
			if (NULL != $conditions) $where = "WHERE " . $conditions;
		}
		foreach ($row as $key => $value) {
			$value  = $this->escape($value);
			$vals[] = "{$key} = {$value}";
		}
		$values = join(", ", $vals);
		$sql    = "UPDATE {$this->tbl_name} SET {$values} {$where}";
		return $this->_db->exec($sql);
	}

	/**
	 * 替换数据，根据条件替换存在的记录，如记录不存在，则将条件与替换数据相加并新增一条记录。
	 * @param conditions    数组形式，查找条件，请注意，仅能使用数组作为该条件！
	 * @param row           数组形式，修改的数据
	 */
	public function replace($conditions, $row)
	{
		if ($this->find($conditions)) {
			return $this->update($conditions, $row);
		} else {
			if (!is_array($conditions)) FeiError('replace方法的条件务必是数组形式！');
			$rows = FeiConfigReady($conditions, $row);
			return $this->create($rows);
		}
	}

	/**
	 * 为设定的字段值增加
	 * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param field         字符串，需要增加的字段名称，该字段务必是数值类型
	 * @param optval        增加的值
	 */
	public function incrField($conditions, $field, $optval = 1)
	{
		$where = "";
		if (is_array($conditions)) {
			$join = array();
			foreach ($conditions as $key => $condition) {
				$condition = $this->escape($condition);
				$join[]    = "{$key} = {$condition}";
			}
			$where = "WHERE " . join(" AND ", $join);
		} else {
			if (NULL != $conditions) $where = "WHERE " . $conditions;
		}
		$values = "{$field} = {$field} + {$optval}";
		$sql    = "UPDATE {$this->tbl_name} SET {$values} {$where}";
		return $this->_db->exec($sql);
	}

	/**
	 * 为设定的字段值减少
	 * @param conditions    数组形式，查找条件，此参数的格式用法与find/findAll的查找条件参数是相同的。
	 * @param field         字符串，需要减少的字段名称，该字段务必是数值类型
	 * @param optval        减少的值
	 */
	public function decrField($conditions, $field, $optval = 1)
	{
		return $this->incrField($conditions, $field, -$optval);
	}

	/**
	 * 按给定的数据表的主键删除记录
	 * @param pk    字符串或数字，数据表主键的值。
	 */
	public function deleteByPk($pk)
	{
		return $this->delete(array($this->pk => $pk));
	}

	/**
	 * 按表字段调整适合的字段
	 * @param rows    输入的表字段
	 */
	private function __prepera_format($rows)
	{
		$columns = $this->_db->getTable($this->tbl_name);
		$newcol  = array();
		foreach ($columns as $col) {
			$newcol[$col['Field']] = $col['Field'];
		}
		return array_intersect_key($rows, $newcol);
	}

}


/**
 * FeiPager
 * 数据分页程序
 */
class FeiPager
{
	/**
	 * 模型对象
	 */
	private $model_obj = NULL;
	/**
	 * 页码数据
	 */
	private $pageData = NULL;
	/**
	 * 调用时输入的参数
	 */
	private $input_args = NULL;

	/**
	 * 函数式使用模型辅助类的输入函数
	 */
	public function __input(& $obj, $args)
	{
		$this->model_obj  = $obj;
		$this->input_args = $args;
		return $this;
	}

	/**
	 * 魔术函数，支持多重函数式使用类的方法
	 */
	public function __call($func_name, $func_args)
	{
		if (('findAll' == $func_name || 'findSql' == $func_name) && 0 != $this->input_args[0]) {
			return $this->runpager($func_name, $func_args);
		} elseif (method_exists($this, $func_name)) {
			return call_user_func_array(array($this, $func_name), $func_args);
		} else {
			return call_user_func_array(array($this->model_obj, $func_name), $func_args);
		}
	}

	/**
	 * 获取分页数据
	 */
	public function getPager()
	{
		return $this->pageData;
	}

	/**
	 * 生成分页数据
	 */
	private function runpager($func_name, $func_args)
	{
		$this->pageData = NULL;
		$page           = $this->input_args[0];
		$pageSize       = $this->input_args[1];
		@list($conditions, $sort, $fields) = $func_args;
		if ('findSql' == $func_name) {
			$total_count = array_pop(array_pop($this->model_obj->findSql("SELECT COUNT({$this->model_obj->pk}) as Fei_counter FROM ($conditions) Fei_tmp_table_pager1")));
		} else {
			$total_count = $this->model_obj->findCount($conditions);
		}
		if ($total_count > $pageSize) {
			$total_page     = ceil($total_count / $pageSize);
			$page           = min(intval(max($page, 1)), $total_count); // 对页码进行规范运算
			$this->pageData = array(
				"total_count"  => $total_count, // 总记录数
				"page_size"    => $pageSize, // 分页大小
				"total_page"   => $total_page, // 总页数
				"first_page"   => 1, // 第一页
				"prev_page"    => ((1 == $page) ? 1 : ($page - 1)), // 上一页
				"next_page"    => (($page == $total_page) ? $total_page : ($page + 1)), // 下一页
				"last_page"    => $total_page, // 最后一页
				"current_page" => $page, // 当前页
				"all_pages"    => array() // 全部页码
			);
			for ($i = 1; $i <= $total_page; $i++) $this->pageData['all_pages'][] = $i;
			$limit = ($page - 1) * $pageSize . "," . $pageSize;
			if ('findSql' == $func_name) $conditions = $this->model_obj->_db->setlimit($conditions, $limit);
		}
		if ('findSql' == $func_name) {
			return $this->model_obj->findSql($conditions);
		} else {
			return $this->model_obj->findAll($conditions, $sort, $fields, $limit);
		}
	}
}

/**
 * FeiVerifier
 * 数据验证程序
 */
class FeiVerifier
{

	/**
	 * 附加的检验规则函数
	 */
	private $add_rules = NULL;

	/**
	 * 验证规则
	 */
	private $verifier = NULL;

	/**
	 * 验证时返回的提示信息
	 */
	private $messages = NULL;

	/**
	 * 待验证字段
	 */
	private $checkvalues = NULL;

	/**
	 * 函数式使用模型辅助类的输入函数
	 */
	public function __input(& $obj, $args)
	{
		$this->verifier = (NULL != $obj->verifier) ? $obj->verifier : array();
		if (isset($args[1]) && is_array($args[1])) {
			$this->verifier["rules"]    = $this->verifier["rules"] + $args[1]["rules"];
			$this->verifier["messages"] = isset($args[1]["messages"]) ? ($this->verifier["messages"] + $args[1]["messages"]) : $this->verifier["messages"];
		}
		if (is_array($obj->addrules) && !empty($obj->addrules)) {
			foreach ($obj->addrules as $addrule => $addveri) $this->addrules($addrule, $addveri);
		}
		if (empty($this->verifier["rules"])) FeiError("无对应的验证规则！");
		return is_array($args[0]) ? $this->checkrules($args[0]) : TRUE; // TRUE为不通过验证
	}

	/**
	 * 加入附加的验证规则
	 * @param rule_name    验证规则名称
	 * @param checker      验证器，验证器可以有两种方式：
	 *                     第一种是  '验证函数名'，这是当函数是一个单纯的函数时使用
	 *                     第二种是 array('类名', '方法函数名')，这是当函数是一个类的某个方法函数时候使用。
	 */
	public function addrules($rule_name, $checker)
	{
		$this->add_rules[$rule_name] = $checker;
	}

	/**
	 * 按规则验证数据
	 * @param values    验证值
	 */
	private function checkrules($values)
	{
		$this->checkvalues = $values;
		foreach ($this->verifier["rules"] as $rkey => $rval) {
			$inputval = isset($values[$rkey]) ? $values[$rkey] : '';
			foreach ($rval as $rule => $rightval) {
				if (method_exists($this, $rule)) {
					if (TRUE == $this->$rule($inputval, $rightval)) continue;
				} elseif (NULL != $this->add_rules && isset($this->add_rules[$rule])) {
					if (function_exists($this->add_rules[$rule])) {
						if (TRUE == $this->add_rules[$rule]($inputval, $rightval, $values)) continue;
					} elseif (is_array($this->add_rules[$rule])) {
						if (TRUE == FeiClass($this->add_rules[$rule][0])->{$this->add_rules[$rule][1]}($inputval, $rightval, $values)) continue;
					}
				} else {
					FeiError("未知规则：{$rule}");
				}
				$this->messages[$rkey][] = (isset($this->verifier["messages"][$rkey][$rule])) ? $this->verifier["messages"][$rkey][$rule] : "{$rule}";
			}
		}
		// 返回FALSE则通过验证，返回数组则未能通过验证，返回的是提示信息。
		return (NULL == $this->messages) ? FALSE : $this->messages;
	}

	/**
	 * 内置验证器，检查字符串非空
	 * @param val      待验证字符串
	 * @param right    正确值
	 */
	private function notnull($val, $right)
	{
		return $right === (strlen($val) > 0);
	}

	/**
	 * 内置验证器，检查字符串是否小于指定长度
	 * @param val      待验证字符串
	 * @param right    正确值
	 */
	private function minlength($val, $right)
	{
		return $this->cn_strlen($val) >= $right;
	}

	/**
	 * 内置验证器，检查字符串是否大于指定长度
	 * @param val      待验证字符串
	 * @param right    正确值
	 */
	private function maxlength($val, $right)
	{
		return $this->cn_strlen($val) <= $right;
	}

	/**
	 * 内置验证器，检查字符串是否等于另一个验证字段的值
	 * @param val      待验证字符串
	 * @param right    正确值
	 */
	private function equalto($val, $right)
	{
		return $val == $this->checkvalues[$right];
	}

	/**
	 * 内置验证器，检查字符串是否正确的时间格式
	 * @param val      待验证字符串
	 * @param right    正确值
	 */
	private function istime($val, $right)
	{
		$test = @strtotime($val);
		return $right == ($test !== -1 && $test !== FALSE);
	}

	/**
	 * 内置验证器，检查字符串是否正确的电子邮件格式
	 * @param val      待验证字符串
	 * @param right    正确值
	 */
	private function email($val, $right)
	{
		return $right == (preg_match('/^[A-Za-z0-9]+([._\-\+]*[A-Za-z0-9]+)*@([A-Za-z0-9-]+\.)+[A-Za-z0-9]+$/', $val) != 0);
	}

	/**
	 * 计算字符串长度，支持包括汉字在内的字符串
	 * @param val    待计算的字符串
	 */
	public function cn_strlen($val)
	{
		$i = 0;
		$n = 0;
		while ($i < strlen($val)) {
			$clen = (strlen("快速") == 4) ? 2 : 3;
			if (preg_match("/^[" . chr(0xa1) . "-" . chr(0xff) . "]+$/", $val[$i])) {
				$i += $clen;
			} else {
				$i += 1;
			}
			$n += 1;
		}
		return $n;
	}
}

/**
 * FeiCache
 * 函数和数据缓存实现
 */
class FeiCache
{

	/**
	 * 默认的数据生存期
	 */
	public $life_time = 3600;

	/**
	 * 模型对象
	 */
	private $model_obj = NULL;

	/**
	 * 调用时输入的参数
	 */
	private $input_args = NULL;

	/**
	 * 函数式使用模型辅助类的输入函数
	 */
	public function __input(& $obj, $args)
	{
		$this->model_obj  = $obj;
		$this->input_args = $args;
		return $this;
	}

	/**
	 * 魔术函数，支持多重函数式使用类的方法
	 */
	public function __call($func_name, $func_args)
	{
		if (isset($this->input_args[0]) && -1 == $this->input_args[0]) return $this->clear($this->model_obj, $func_name, $func_args);
		$cache_id = get_class($this->model_obj) . md5($func_name);
		if (NULL != $func_args) $cache_id .= md5(json_encode($func_args));
		if ($cache_file = FeiAccess('r', "Fei_cache_{$cache_id}")) return unserialize($cache_file);
		return $this->cache_obj($cache_id, call_user_func_array(array($this->model_obj, $func_name), $func_args), $this->input_args[0]);
	}

	/**
	 * 执行FeiModel子类对象的方法，并对返回结果进行缓存。
	 * @param obj          引用的FeiModel子类对象
	 * @param func_name    需要执行的函数名称
	 * @param func_args    函数的参数
	 * @param life_time    缓存生存时间
	 */
	public function cache_obj($cache_id, $run_result, $life_time = NULL)
	{
		if (NULL == $life_time) $life_time = $this->life_time;
		FeiAccess('w', "Fei_cache_{$cache_id}", serialize($run_result), $life_time);
		if ($cache_list = FeiAccess('r', 'Fei_cache_list')) {
			$cache_list = explode("\n", $cache_list);
			if (!in_array($cache_id, $cache_list)) FeiAccess('w', 'Fei_cache_list', join("\n", $cache_list) . $cache_id . "\n");
		} else {
			FeiAccess('w', 'Fei_cache_list', $cache_id . "\n");
		}
		return $run_result;
	}

	/**
	 * 清除单个函数缓存的数据
	 * @param obj          引用的FeiModel子类对象
	 * @param func_name    需要执行的函数名称
	 * @param func_args    函数的参数，在默认不输入参数的情况下，将清除全部该函数生成的缓存。
	 *                     如果func_args有设置，将只会清除该参数产生的缓存。
	 */
	public function clear(& $obj, $func_name, $func_args = NULL)
	{
		$cache_id = get_class($obj) . md5($func_name);
		if (NULL != $func_args) $cache_id .= md5(json_encode($func_args));
		if ($cache_list = FeiAccess('r', 'Fei_cache_list')) {
			$cache_list = explode("\n", $cache_list);
			$new_list   = '';
			foreach ($cache_list as $single_item) {
				if ($single_item == $cache_id || (NULL == $func_args && substr($single_item, 0, strlen($cache_id)) == $cache_id)) {
					FeiAccess('c', "Fei_cache_{$single_item}");
				} else {
					$new_list .= $single_item . "\n";
				}
			}
			FeiAccess('w', 'Fei_cache_list', substr($new_list, 0, -1));
		}
		return TRUE;
	}

	/**
	 * 清除全部函数缓存的数据

	 */
	public function clear_all()
	{
		if ($cache_list = FeiAccess('r', 'Fei_cache_list')) {
			$cache_list = explode("\n", $cache_list);
			foreach ($cache_list as $single_item) FeiAccess('c', "Fei_cache_{$single_item}");
			FeiAccess('c', 'Fei_cache_list');
		}
		return TRUE;
	}
}

/**
 * FeiLinker
 * 数据库的表间关联程序
 */
class FeiLinker
{
	/**
	 * 模型对象
	 */
	private $model_obj = NULL;

	/**
	 * 预准备的结果
	 */
	private $prepare_result = NULL;

	/**
	 * 运行的结果
	 */
	private $run_result = NULL;

	/**
	 * 可支持的关联方法
	 */
	private $methods = array('find', 'findBy', 'findAll', 'run', 'create', 'delete', 'deleteByPk', 'update');
	/**
	 * 是否启用全部关联
	 */
	public $enabled = TRUE;

	/**
	 * 函数式使用模型辅助类的输入函数
	 */
	public function __input(& $obj, $args = NULL)
	{
		$this->model_obj = $obj;
		return $this;
	}

	/**
	 * 开发者可以通过FeiLinker()->run($result)对已经返回的数据进行关联findAll查找
	 * @param result    返回的数据
	 */
	public function run($result = FALSE)
	{
		if (FALSE == $result) return FALSE;
		$this->run_result = $result;
		return $this->__call('run', NULL);
	}

	/**
	 * 魔术函数，支持多重函数式使用类的方法
	 * 在FeiLinker类中，__call执行了FeiModel继承类的相关操作，以及按关联的描述进行了对关联数据模型类的操作。
	 */
	public function __call($func_name, $func_args)
	{
		if (in_array($func_name, $this->methods) && FALSE != $this->enabled) {
			if ('delete' == $func_name || 'deleteByPk' == $func_name) $maprecords = $this->prepare_delete($func_name, $func_args);
			if (NULL != $this->run_result) {
				$run_result = $this->run_result;
			} elseif (!$run_result = call_user_func_array(array($this->model_obj, $func_name), $func_args)) {
				if ('update' != $func_name) return FALSE;
			}
			if (NULL != $this->model_obj->linker && is_array($this->model_obj->linker)) {
				foreach ($this->model_obj->linker as $linkey => $thelinker) {
					if (!isset($thelinker['map'])) $thelinker['map'] = $linkey;
					if (FALSE == $thelinker['enabled']) continue;
					$thelinker['type'] = strtolower($thelinker['type']);
					if ('find' == $func_name || 'findBy' == $func_name) {
						$run_result[$thelinker['map']] = $this->do_select($thelinker, $run_result);
					} elseif ('findAll' == $func_name || 'run' == $func_name) {
						foreach ($run_result as $single_key => $single_result)
							$run_result[$single_key][$thelinker['map']] = $this->do_select($thelinker, $single_result);
					} elseif ('create' == $func_name) {
						$this->do_create($thelinker, $run_result, $func_args);
					} elseif ('update' == $func_name) {
						$this->do_update($thelinker, $func_args);
					} elseif ('delete' == $func_name || 'deleteByPk' == $func_name) {
						$this->do_delete($thelinker, $maprecords);
					}
				}
			}
			return $run_result;
		} elseif (in_array($func_name, $GLOBALS['G_Fei']["auto_load_model"])) {
			return FeiClass($func_name)->__input($this, $func_args);
		} else {
			return call_user_func_array(array($this->model_obj, $func_name), $func_args);
		}
	}

	/**
	 * 私有函数，辅助删除数据操作
	 * @param func_name    需要执行的函数名称
	 * @param func_args    函数的参数
	 */
	private function prepare_delete($func_name, $func_args)
	{
		if ('deleteByPk' == $func_name) {
			return $this->model_obj->findAll(array($this->model_obj->pk => $func_args[0]));
		} else {
			return $this->model_obj->findAll($func_args[0]);
		}
	}

	/**
	 * 私有函数，进行关联删除数据操作
	 * @param thelinker     关联的描述
	 * @param maprecords    对应的记录
	 */
	private function do_delete($thelinker, $maprecords)
	{
		if (FALSE == $maprecords) return FALSE;
		foreach ($maprecords as $singlerecord) {
			if (!empty($thelinker['condition'])) {
				if (is_array($thelinker['condition'])) {
					$fcondition = array($thelinker['fkey'] => $singlerecord[$thelinker['mapkey']]) + $thelinker['condition'];
				} else {
					$fcondition = "{$thelinker['fkey']} = '{$singlerecord[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
				}
			} else {
				$fcondition = array($thelinker['fkey'] => $singlerecord[$thelinker['mapkey']]);
			}
			$returns = FeiClass($thelinker['fclass'])->delete($fcondition);
		}
		return $returns;
	}

	/**
	 * 私有函数，进行关联更新数据操作
	 * @param thelinker    关联的描述
	 * @param func_args    进行操作的参数
	 */
	private function do_update($thelinker, $func_args)
	{
		if (!is_array($func_args[1][$thelinker['map']])) return FALSE;
		if (!$maprecords = $this->model_obj->findAll($func_args[0])) return FALSE;
		foreach ($maprecords as $singlerecord) {
			if (!empty($thelinker['condition'])) {
				if (is_array($thelinker['condition'])) {
					$fcondition = array($thelinker['fkey'] => $singlerecord[$thelinker['mapkey']]) + $thelinker['condition'];
				} else {
					$fcondition = "{$thelinker['fkey']} = '{$singlerecord[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
				}
			} else {
				$fcondition = array($thelinker['fkey'] => $singlerecord[$thelinker['mapkey']]);
			}
			$returns = FeiClass($thelinker['fclass'])->update($fcondition, $func_args[1][$thelinker['map']]);
		}
		return $returns;
	}

	/**
	 * 私有函数，进行关联新增数据操作
	 * @param thelinker    关联的描述
	 * @param newid        主表新增记录后的关联ID
	 * @param func_args    进行操作的参数
	 */
	private function do_create($thelinker, $newid, $func_args)
	{
		if (!is_array($func_args[0][$thelinker['map']])) return FALSE;
		if ('hasone' == $thelinker['type']) {
			$newrows                     = $func_args[0][$thelinker['map']];
			$newrows[$thelinker['fkey']] = $newid;
			return FeiClass($thelinker['fclass'])->create($newrows);
		} elseif ('hasmany' == $thelinker['type']) {
			if (array_key_exists(0, $func_args[0][$thelinker['map']])) { // 多个新增
				foreach ($func_args[0][$thelinker['map']] as $singlerows) {
					$newrows                     = $singlerows;
					$newrows[$thelinker['fkey']] = $newid;
					$returns                     = FeiClass($thelinker['fclass'])->create($newrows);
				}
				return $returns;
			} else { // 单个新增
				$newrows                     = $func_args[0][$thelinker['map']];
				$newrows[$thelinker['fkey']] = $newid;
				return FeiClass($thelinker['fclass'])->create($newrows);
			}
		}
	}

	/**
	 * 私有函数，进行关联查找数据操作
	 * @param thelinker     关联的描述
	 * @param run_result    主表执行查找后返回的结果
	 */
	private function do_select($thelinker, $run_result)
	{
		if (empty($thelinker['mapkey'])) $thelinker['mapkey'] = $this->model_obj->pk;
		if ('manytomany' == $thelinker['type']) {
			$do_func      = 'findAll';
			$midcondition = array($thelinker['mapkey'] => $run_result[$thelinker['mapkey']]);
			if (!$midresult = FeiClass($thelinker['midclass'])->findAll($midcondition, NULL, $thelinker['fkey'])) return FALSE;
			$tmpkeys = array();
			foreach ($midresult as $val) $tmpkeys[] = "'" . $val[$thelinker['fkey']] . "'";
			if (!empty($thelinker['condition'])) {
				if (is_array($thelinker['condition'])) {
					$fcondition = "{$thelinker['fkey']} in (" . join(',', $tmpkeys) . ")";
					foreach ($thelinker['condition'] as $tmpkey => $tmpvalue) $fcondition .= " AND {$tmpkey} = '{$tmpvalue}'";
				} else {
					$fcondition = "{$thelinker['fkey']} in (" . join(',', $tmpkeys) . ") AND {$thelinker['condition']}";
				}
			} else {
				$fcondition = "{$thelinker['fkey']} in (" . join(',', $tmpkeys) . ")";
			}
		} else {
			$do_func = ('hasone' == $thelinker['type']) ? 'find' : 'findAll';
			if (!empty($thelinker['condition'])) {
				if (is_array($thelinker['condition'])) {
					$fcondition = array($thelinker['fkey'] => $run_result[$thelinker['mapkey']]) + $thelinker['condition'];
				} else {
					$fcondition = "{$thelinker['fkey']} = '{$run_result[$thelinker['mapkey']]}' AND {$thelinker['condition']}";
				}
			} else {
				$fcondition = array($thelinker['fkey'] => $run_result[$thelinker['mapkey']]);
			}
		}
		if (TRUE == $thelinker['countonly']) $do_func = "findCount";
		return FeiClass($thelinker['fclass'])->$do_func($fcondition, $thelinker['sort'], $thelinker['field'], $thelinker['limit']);
	}
}
