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
                /*添加密码*/
                $this->addPwd($password, $data);
				break;
			case 'get_pwd':
                /*获得密码列表*/
                $pwdList = $this->getPwd($password, $data);
                echo $pwdList;
				exit;
			case 'del_pwd':
                /*删除一条密码信息*/
                $this->delPwd($password, $data);
                break;
			case 'get_info':
                /*获得密码信息*/
                $this->getInfo($password, $data);
                break;
			case 'edit_pwd':
                /*更新密码信息*/
                $this->editPwd($password, $data);
                break;
			case 'pcates':
                /*获取密码分类列表*/
                $pactes = $this->getPCate($pcate);
                echo $pactes;
				exit;
			case 'add_cate':
                /*添加分类*/
                $this->addCate($pcate, $data);
                break;
			default:
				$this->pcates = $pcate->pcates();
				break;
		}
	}

    /**
     * 添加密码信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $password
     * @param $data
     */
    private function addPwd($password, $data)
    {
        $this->__check_istrue($password->add($data));
    }

    /**
     * 获取密码列表
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $password
     * @param $data
     */
    private function getPwd($password, $data)
    {
        $id        = $data['id'];
        $passwords = $password->pwds($id);
        $password_html = "";
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
        return $password_html;
    }

    /**
     * 删除一条密码信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $password
     * @param $data
     */
    private function delPwd($password, $data)
    {
        $this->__check_istrue($password->del($data));
    }


    /**
     * 获取一条密码信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $password
     * @param $data
     */
    private function getInfo($password, $data)
    {
        $this->__check_istrue($info = $password->info($data), $info);
    }

    /**
     * 修改密码信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $password
     * @param $data
     */
    private function editPwd($password, $data)
    {
        $resultBool = $this->__check_istrue($password->edit($data));
        return $resultBool;
    }

    /**
     * 获取密码分类列表
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $pcate
     */
    private function getPCate($pcate){
        $pcates = $pcate->pcates();
        $html = "";
        for ($i = 0; $i < count($pcates); $i++) {
            $html .= "
					<div>
	                    <span class=\"badge blue light icon-user-md\">
	                        " . $pcates[$i]['name'] . "
	                    </span>
	                    <div class=\"right inline\" id=\"m_b\">
	                        <a href=\"javascript:void(0)\" onclick=\"Todo.edit(".$pcates[$i]['id'].")\" class=\"button small grey tooltip\" data-gravity=\"e\" original-title=\"修改\">
	                            <i class=\"icon-pencil\"></i>
	                        </a>
	                        <a href=\"javascript:void(0)\" onclick=\"Todo.del_tag(".$pcates[$i]['id'].")\" class=\"button small grey tooltip\" gravity=\"w\" title=\"删除\" >
	                            <i class=\"icon-remove\"></i>
	                        </a>
	                    </div>
	                </div>
					";
        }
        return $html;
    }

    /**
     * 添加分类的方法
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $pcate
     * @param $data
     */
    private function addCate($pcate, $data)
    {
        $this->__check_istrue($pcate->add($data));
    }

}