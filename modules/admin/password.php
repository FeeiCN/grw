<?php

/**
 * 密码保险箱
 * Class password
 */
class password extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('password');
	}

	function index()
	{
		$password = FeiClass('model_password');
		$pcate    = FeiClass('model_password_cate');
		$action   = $this->FeiArgs('action', NULL, 'POST');
		$data     = $this->FeiArgs(NULL, NULL, 'POST');
		switch ($action) {
			case 'add_pwd':
				$this->__check_istrue($password->add($data));
				break;
			case 'get_password':
				$id        = $data['id'];
				$passwords = $password->pwds($id);
				for ($i = 0; $i < count($passwords); $i++) {
					$password_html .= "
						<tr>
                            <td class=\"center\">
                                " . $i . "
                            </td>
                            <td class=\"center\">
                                " . $passwords[$i]['title'] . "
                            </td>
                            <td class=\"center\">
                                " . $passwords[$i]['username'] . "
                            </td>
                            <td class=\"center\">
                            	" . $passwords[$i]['password'] . "
                            </td>
                            <td class=\"center\">
                                " . $passwords[$i]['remark'] . "
                            </td>
                            <td class=\"center\">
                            	<a onclick=\"Pwd.edit(" . $passwords[$i]['id'] . ")\" class=\"button small grey tooltip\" gravity=\"s\" title=\"修改\"><i class=\"icon-pencil\"></i></a>
                            	<a onclick=\"Pwd.del(" . $passwords[$i]['id'] . ")\"  class=\"button small grey tooltip\" gravity=\"s\" title=\"删除\"><i class=\"icon-remove\"></i></a>
                            </td>
                        </tr>
					";
				}
				echo $password_html;
				exit;
			case 'del_pwd':
				$this->__check_istrue($password->del($data));
			case 'get_info':
				$this->__check_istrue($info = $password->info($data), $info);
			case 'edit_pwd':
				$this->__check_istrue($password->edit($data));
			case 'pcates':
				$pcates = $pcate->pcates();
				for ($i = 0; $i < count($pcates); $i++) {
					$html .= "
					<div>
	                    <span class=\"badge blue light icon-user-md\">
	                        " . $pcates[$i]['name'] . "
	                    </span>
	                    <!--<div class=\"right inline\" id=\"m_b\">
	                        <a href=\"javascript:void(0)\" class=\"button small grey tooltip\" data-gravity=\"e\" original-title=\"修改\">
	                            <i class=\"icon-pencil\"></i>
	                        </a>
	                        <a href=\"javascript:void(0)\" onclick=\"Todo.del_tag(1)\" class=\"button small grey tooltip\" gravity=\"w\" title=\"删除" . $pcates[$i]['id'] . "\">
	                            <i class=\"icon-remove\"></i>
	                        </a>
	                    </div>-->
	                </div>
					";
				}
				echo $html;
				exit;
			case 'add_cate':
				$this->__check_istrue($pcate->add($data));
			default:
				$this->pcates = $pcate->pcates();
				break;
		}
	}
}