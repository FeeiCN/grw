<?php

class model_money_bank extends FeiModel
{
	var $pk = "id";
	var $table = "money_bank";

	public function banks()
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid']
		);
		return $this->findAll($con);
	}

	public function info($data)
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid'],
			'id'  => $data['id']
		);
		return $this->find($con);
	}

	public function add($data)
	{
		$row = array(
			'uid'     => $_SESSION['Fei_Userid'],
			'title'   => $data['bank_title'],
			'type'    => $data['bank_type'],
			'num'     => $data['bank_num'],
			'ctime'   => $data['bank_ctime'],
			'reserve' => $data['bank_reserve']
		);
		return $this->create($row);
	}

	public function edit($data)
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid'],
			'id'  => $data['edit_id']
		);
		if ($this->find($con)) {
			$row = array(
				'title'   => $data['bank_title'],
				'type'    => $data['bank_type'],
				'num'     => $data['bank_num'],
				'ctime'   => $data['bank_ctime'],
				'reserve' => $data['bank_reserve']
			);
			return $this->update($con, $row);
		} else {
			return FALSE;
		}
	}

	public function sum()
	{
		$con    = array(
			'uid' => $_SESSION['Fei_Userid'],
		);
		$result = $this->findAll($con);
		if (count($result) != 0) {
			$num = 0;
			for ($i = 0; $i < count($result); $i++) {
				$num = $num + intval($result[$i]['reserve']);
			}
		} else {
			$num = 0;
		}
		return $num;
	}
}