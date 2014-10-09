<?php

class model_yunpan extends FeiModel
{
	var $pk = "id";
	var $table = 'yunpan';

	public function index()
	{
		$condition = array(
			'user_id' => $_SESSION['Fei_Userid']
		);
		$result    = $this->find($condition);
		return $result['id'];
	}

	public function create_drive()
	{
		$sql   = "select * from Fei_user where id=" . $_SESSION['Fei_Userid'];
		$rs    = $this->findSql($sql);
		$row   = array(
			'parent_id' => 0,
			'name'      => $rs[0]['username'],
			'user_id'   => $_SESSION['Fei_Userid'],
			'mime'      => 'directory'
		);
		$newId = $this->create($row);
		return $newId;
	}
}