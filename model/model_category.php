<?php

/**
 * 栏目控制
 * Class model_category
 */
class model_category extends FeiModel
{
	var $pk = "catid";
	var $table = "category";
	var $verifier = array(
		"rules"    => array(
			"catname" => array(
				'notnull' => TRUE
			)
		),
		"messages" => array(
			"catname" => array(
				'notnull' => '栏目名称不能为空'
			)
		)
	);

	function topcategory()
	{
		$con = array(
			'parentid' => 0
		);
		return $this->findAll($con, 'listorder ASC');
	}
}