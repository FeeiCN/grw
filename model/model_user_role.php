<?php

/**
 * 用户角色
 * Class model_user_role
 */
class model_user_role extends FeiModel
{
	var $pk = "id";
	var $table = "user_role";

	/**
	 * 根据RoleId得到角色名
	 * @param $roleId
	 * @return bool|mixed
	 */
	public function getNameById($roleId)
	{
		$con = array(
			'id' => $roleId
		);
		$name = $this->find($con, NULL, 'name');
		return $name['name'];
	}
}