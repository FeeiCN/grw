<?php

/**
 * 团队协作（待废弃）
 * Class cooperation
 */
class cooperation extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('cooperation');
	}

	function index()
	{
		$user        = FeiClass(model_user);
		$this->users = $user->findAll();
	}

	function person()
	{
		$user        = FeiClass(model_user);
		$this->users = $user->findAll();
	}

	function branch()
	{
		$branch  = FeiClass(model_system);
		$branchs = $branch->findAll('', '', 'branch');
		$branchs = explode('|', $branchs[0][branch]);

		$user = FeiClass(model_user);

		foreach ($branchs as $k => $v) {
			$branchs['key' . $k]['zhi'] = $v;
			$condition                  = array('branch' => $v);
			$branchs['key' . $k]['num'] = $user->findCount($condition);
			unset($branchs[$k]);
		}


		//print_r($branchs);
		$this->branchs = $branchs;
	}

}