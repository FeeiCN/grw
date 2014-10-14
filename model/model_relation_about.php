<?php

class model_relation_about extends FeiModel
{
	var $pk = "id";
	var $table = "relation_about";

	public function getAll($userid)
	{
		$con = array(
			'uid'    => $_SESSION['Fei_Userid'],
			'userid' => $userid
		);
		return $this->findAll($con);
	}

	public function add($data)
	{
		$con = array(
			'uid'    => $_SESSION['Fei_Userid'],
			'userid' => $data['userid'],
			'name'   => $data['name'],
			'value'  => $data['value']
		);
		return $this->create($con);
	}
}