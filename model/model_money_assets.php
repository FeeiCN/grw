<?php

class model_money_assets extends FeiModel
{
	var $pk = "id";
	var $table = "money_assets";

	public function getAll()
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid']
		);
		return $this->findAll($con);
	}

	public function add()
	{
		$arr = safe_replace($_POST);
		$row = array(
			'uid'    => $_SESSION['Fei_Userid'],
			'name'   => $arr['name'],
			'sum'    => $arr['sum'],
			'time'   => $arr['time'],
			'remark' => $arr['remark']
		);
		return $this->create($row);
	}

	public function del()
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid'],
			'id'  => intval($_POST['id'])
		);
		return $this->delete($con);
	}

	public function sum()
	{
		$all = $this->getAll();
		for ($i = 0; $i < count($all); $i++) {
			$num += $all[$i][sum];
		}
		return $num;
	}
}