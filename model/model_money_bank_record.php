<?php

class model_money_bank_record extends FeiModel
{
	var $pk = "id";
	var $table = "money_bank_record";


	public function get_one($bankid)
	{
		$con = array(
			'uid'    => $_SESSION['Fei_Userid'],
			'bankid' => $bankid
		);
		return $this->findAll($con, 'ctime DESC', NULL, '10');
	}

	public function record($data)
	{
		$cz      = $data['cz'];
		$bankid  = $data['bankid'];
		$ctime   = $data['ctime'];
		$sum     = $data['sum'];
		$address = $data['address'];
		$remark  = $data['remark'];
		$row     = array(
			'cz'      => $cz,
			'bankid'  => $bankid,
			'ctime'   => $ctime,
			'sum'     => $sum,
			'address' => $address,
			'remark'  => $remark,
			'uid'     => $_SESSION['Fei_Userid']
		);
		return $this->create($row);
	}
}