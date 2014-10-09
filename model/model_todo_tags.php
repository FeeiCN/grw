<?php

class model_todo_tags extends FeiModel
{
	var $pk = "tagid";
	var $table = "todo_tags";

	public function add($data)
	{
		$row = array(
			'name'   => $data['name'],
			'icon'   => $data['icon'],
			'userid' => $_SESSION['Fei_Userid']
		);
		return $this->create($row);
	}

	public function del($data)
	{
		$con = array(
			'tagid'  => $data['tagid'],
			'userid' => $_SESSION['Fei_Userid']
		);
		return $this->delete($con);
	}

	/**
	 * getAll(搜集所有TAGID的TAG总数)
	 * @return type Tags|false
	 */
	public function getAll()
	{
		$con = array(
			'userid' => $_SESSION['Fei_Userid']
		);
		//先更新用户TAG的TOTAL
		$tagids = $this->findAll(NULL, NULL, 'tagid');
		for ($i = 0; $i <= count($tagids); $i++) {
			$this->total($tagids[$i]['tagid']);
		}
		return $this->findAll($con);
	}

	/**
	 * 更新TAG的Total
	 * @param type $tagid
	 * @return boolean
	 */
	public function total($tagid)
	{
		$todo = FeiClass('model_todo');
		$con  = array(
			'tagid'  => $tagid,
			'userid' => $_SESSION['Fei_Userid']
		);
		if ($this->find($con)) {
			$con2 = array(
				'status' => 0,
				'userid' => $_SESSION['Fei_Userid'],
				'tags'   => $tagid
			);
			$num  = $todo->findCount($con2);
			$row  = array(
				'total' => $num
			);
			$this->update($con, $row);
		} else {
			return FALSE;
		}
	}

	public function maybe($tagid)
	{
		$todo = FeiClass('model_todo');
		$con  = array(
			'tagid'  => $tagid,
			'userid' => $_SESSION['Fei_Userid']
		);
		if ($this->find($con)) {
			$con2 = array(
				'status' => 3,
				'userid' => $_SESSION['Fei_Userid'],
				'tags'   => $tagid
			);
			$num  = $todo->findCount($con2);
			$row  = array(
				'maybe' => $num
			);
			return $this->update($con, $row);
		} else {
			return FALSE;
		}
	}
}