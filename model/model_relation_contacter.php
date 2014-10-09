<?php

class model_relation_contacter extends FeiModel
{
	var $pk = "id";
	var $table = "relation_contacter";

	public function add($data)
	{
		$row = array(
			'uid'        => $_SESSION['Fei_Userid'],
			'firstchar'  => $data['firstchar'],
			'name'       => $data['name'],
			'infantname' => $data['infantname'],
			'birthtype'  => $data['birthtype'],
			'birthday'   => $data['birthday'],
			'qq'         => $data['qq'],
			'mobile'     => $data['mobile'],
			'weibo'      => $data['weibo'],
			'birthplace' => $data['birthplace'],
			'email'      => $data['email']
		);
		return $this->create($row);
	}

	public function edit($data)
	{
		$con = array(
			'id'  => $data['cid'],
			'uid' => $_SESSION['Fei_Userid']
		);
		if ($this->find($con)) {
			$row = array(
				'firstchar'  => $data['firstchar'],
				'name'       => $data['name'],
				'infantname' => $data['infantname'],
				'birthtype'  => $data['birthtype'],
				'birthday'   => $data['birthday'],
				'qq'         => $data['qq'],
				'mobile'     => $data['mobile'],
				'weibo'      => $data['weibo'],
				'birthplace' => $data['birthplace'],
				'email'      => $data['email']
			);
			return $this->update($con, $row);
		} else {
			return FALSE;
		}
	}

	public function del($data)
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid'],
			'id'  => $data['did']
		);
		return $this->delete($con);
	}

	public function getAll()
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid']
		);
		return $this->findAll($con);
	}

	public function getOne($data)
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid'],
			'id'  => $data['id']
		);
		return $this->find($con);
	}

	public function info($data)
	{
		$id  = intval($data['id']);
		$con = array(
			'uid' => $_SESSION['Fei_Userid'],
			'id'  => $id
		);
		return $this->find($con);
	}

	public function getchar($char)
	{
		$con = array(
			'uid'       => $_SESSION['Fei_Userid'],
			'firstchar' => $char
		);
		return $this->findAll($con);
	}
}