<?php

/**
 * 密码保险箱分类
 * Class model_password_cate
 */
class model_password_cate extends FeiModel
{
	var $pk = "id";
	var $table = "password_cate";

	/**
	 * @field all 是否所有用户都显示
	 **/
	public function pcates()
	{
		$con = array(
			'uid' => $_SESSION['Fei_Userid']
		);
		return $this->findAll($con);
	}

	public function add($data)
	{
		$con = array(
			'uid'  => $_SESSION['Fei_Userid'],
			'name' => $data['cate_title']
		);
		return $this->create($con);
	}
}