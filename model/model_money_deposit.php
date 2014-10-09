<?php

class model_money_deposit extends Feimodel
{
	var $pk = "id";
	var $table = "money_deposit";

	public function add($data)
	{
		$row = array(
			'type'   => $data['type'],
			'user'   => $data['user'],
			'sum'    => $data['sum'],
			'stime'  => $data['stime'],
			'period' => $data['period'],
			'remark' => $data['remark'],
			'uid'    => $_SESSION['Fei_Userid']
		);
		return $this->create($row);
	}

	public function deposits($cz)
	{
		$cz  = intval($cz);
		$con = array(
			'uid'  => $_SESSION['Fei_Userid'],
			'type' => $cz
		);
		return $this->findAll($con, 'stime DESC');
	}

	public function sum()
	{
		$con    = array(
			'uid'  => $_SESSION['Fei_Userid'],
			'type' => 0 #借出
		);
		$result = $this->findAll($con);
		if (count($result) == 0) {
			return 0;
		} else {
			$num = 0;
			for ($i = 0; $i < count($result); $i++) {
				$num = $num + intval($result[$i]['sum']);
			}
			return $num;
		}
	}
}