<?php

/**
 * 迁移准备 1.添加acl表c＝api a＝index
 **/
class api extends Grw
{
	function __construct()
	{
		parent::__construct();

	}

	/**
	 * 供第三方调用的API接口（Chrome Extension、App）
	 * @author Feei(wufeifei@wufeifei.com)
	 */
	function index()
	{
		$action = $this->FeiArgs('action', NULL, 'POST');
		$data   = $this->FeiArgs(NULL, NULL, 'POST');
		switch ($action) {
			case 'is_login':
				if (FeiClass(FeiAcl)->get() == 'Fei_Admin') {
					$this->__check_istrue(TRUE, '已经登陆');
				} else {
					$this->__check_istrue(FALSE, '登陆失败');
				}
				break;

			default:
				# code...
				break;
		}
	}
}