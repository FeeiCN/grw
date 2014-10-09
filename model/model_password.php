<?php

/**
 * 密码保险箱
 * Class model_password
 */
class model_password extends FeiModel
{
	var $pk = "id";
	var $table = "password";

	public function pwds($id)
	{
		if ($this->check_cate($id)) {
			$con = array(
				'catid' => $id,
				'uid'   => $_SESSION['Fei_Userid']
			);
			return $this->findAll($con);
		} else {
			return FALSE;
		}

	}

	public function del($data)
	{
		$id  = intval($data['id']);
		$con = array(
			'id'  => $id,
			'uid' => $_SESSION['Fei_Userid']
		);
		return $this->delete($con);
	}

	public function info($data)
	{
		$id  = intval($data['id']);
		$con = array(
			'id'  => $id,
			'uid' => $_SESSION['Fei_Userid']
		);
		return $this->find($con);
	}

	public function edit($data)
	{
		$id  = intval($data['id']);
		$con = array(
			'id'  => $id,
			'uid' => $_SESSION['Fei_Userid']
		);
		if ($this->find($con)) {
			$row = array(
				'catid'    => $data['password_cate'],
				'title'    => $data['password_title'],
				'website'  => $data['password_website'],
				'username' => $data['password_username'],
				'password' => $data['password_password'],
				'remark'   => $data['password_remark']
			);
			return $this->update($con, $row);
		} else {
			return FALSE;
		}
	}

	function check_cate($id)
	{
		$pwd_cate = FeiClass('model_password_cate');
		$con      = array(
			'id'  => $id,
			'uid' => $_SESSION['Fei_Userid']
		);
		return $pwd_cate->find($con) ? TRUE : FALSE;
	}

	public function add($data)
	{
		$catid = intval($data['catid']);
		if ($this->check_cate($catid)) {
			$row = array(
				'catid'    => $catid,
				'uid'      => $_SESSION['Fei_Userid'],
				'title'    => $data['password_title'],
				'website'  => $data['password_website'],
				'username' => $data['password_username'],
				'password' => $data['password_password'],
				'remark'   => $data['password_remark']
			);
			return $this->create($row);
		} else {
			return FALSE;
		}

	}
}