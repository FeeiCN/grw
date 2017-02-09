<?php
/**
 * 个人信息
 * Class profile
 */
class profile extends Grw
{

    /**
     * 构造方法，用于初始化参数
     */

	function __construct()
	{
		parent::__construct();
		$this->__initial('profile');
	}

    /**
     * 个人信息控制器主方法
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     */
	function index()
	{
		$user      = FeiClass('model_user');
		$education = FeiClass('model_education');
		$work      = FeiClass('model_work');
		$skill     = FeiClass('model_skill');
		$follow    = FeiClass('model_follow');

		if (isset($_POST['action']) && $_POST['action'] == 'edit_profile') { //Edit Profile
            /*修改用户信息*/
            $this->editProfile($user);
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_education') { //Add Education
            /*添加用户教育信息*/
            $this->addEducation($education);
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_education_info') { //Ajax Load Education Info
            /*异步请求教育信息*/
            $educations = $this->loadEducation($education);
            echo $educations;
            exit;
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_work') { //Add Work
            /*添加工作信息*/
            $this ->addWork($work);
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_work_info') { //Ajax Load Work Info
            /*异步请求工作信息*/
            $works =$this->loadWork($work);
            echo $works;
            exit;
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_manage') { //Get Manage Work
            /*异步请求管理界面*/
            $this->loadManageWork($work, $education, $skill, $follow);
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_skill') { //Add Skill
            /*添加技能信息*/
			$this->addSkill($skill);
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_skill_info') { //Ajax Load Skill Info
            /*异步请求技能信息*/
			$skills =$this->loadSkill($skill);
            echo $skills;
            exit;
		}elseif (isset($_POST['action']) && $_POST['action'] == 'add_follow') { //Add Follow
           /*添加社交信息*/
            $this->addFollow($follow);
        } elseif (isset($_POST['action']) && $_POST['action'] == 'get_follow_info') { //Ajax Load Follow Info
            /*异步请求社交关注信息*/
            $infos = $this -> loadFollow($follow);
            echo $infos;
            exit;
		} elseif (isset($_POST['action']) && $_POST['action'] == 'del_type') {
            /*删除信息*/
            $this -> delete($work, $education, $skill, $follow);
		} else {
			//User Info
			$this->user = $user->getCurrentUserInfo();
		}
	}

	function product()
	{
		$product = FeiClass('model_product');
		if (isset($_FILES['product_pic']) && !empty($_FILES['product_pic'])) {
			$upload = FeiClass('FeiUpload');
			if ($upload->upload_file($_FILES['product_pic'])) { //Upload Success
				$conditions = array(
					'name'        => $this->FeiArgs('product_name'),
					'pic'         => $upload->new_name,
					'link'        => $this->FeiArgs('product_link'),
					'description' => $this->FeiArgs('product_desc'),
					'userid'      => $_SESSION['Fei_Userid']
				);
				if ($product->create($conditions)) {
					$this->jump(FeiUrl('profile', 'product'));
				} else {
					$this->error('error', FeiUrl('profile', 'product'));
				}
			} else {
				$this->error($upload->errmsg, FeiUrl('profile', 'product'));
			}
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_product_content') {
			$conditions = array('userid' => $_SESSION['Fei_Userid']);
			$products   = $product->findAll($conditions);
            $str = "";
			foreach ($products as $p) {
				$str .= "<div class=\"image\">
                            <span class=\"badge\">
                                新
                            </span>
                            <a href=\"" . $this->PIC . $p['pic'] . "\">
                            <img src=\"" . $this->PIC . $p['pic'] . "\" width=\"160\" />
                            </a>
                            <span>" . $p['name'] . "</span>
                        </div>";
			}
			echo $str;
			exit;

		}
	}

    function themes()
    {
        #@TODO 主题方法，后续实现
    }


    /**
     * 编辑用户信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $user
     */
    private function editProfile($user)
    {
        $conditions = array(
            'id' => $_SESSION['Fei_Userid']
        );
        $rows       = array(
            'realname'    => $this->FeiArgs('username'),
            'position'    => $this->FeiArgs('position'),
            'birthday'    => $this->FeiArgs('birthday'),
            'email'       => $this->FeiArgs('email'),
            'phone'       => $this->FeiArgs('phone'),
            'qq'          => $this->FeiArgs('qq'),
            'description' => $this->FeiArgs('description')
        );
         $this->__check_istrue($user->update($conditions, $rows));
    }

    /**
     * 添加教育信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $education
     */
    private function addEducation($education)
    {
        $conditions = array(
            'userid'   => $_SESSION['Fei_Userid'],
            'school'   => $this->FeiArgs('school'),
            'type'     => $this->FeiArgs('type'),
            'startime' => $this->FeiArgs('startime'),
            'endtime'  => $this->FeiArgs('endtime')
        );
        $this->__check_istrue($education->create($conditions));
    }

    private function loadEducation($education)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $e_list     = $education->findAll($conditions);
        if (count($e_list) != 0) {
            $str = "";
            foreach ($e_list as $e) {
                switch($e['type']){
                    case 0:
                        $type = '小学';
                        break;
                    case 1:
                        $type = '中学';
                        break;
                    case 2:
                        $type = '高中';
                        break;
                    case 3:
                        $type = '大学';
                        break;
                    case 4:
                        $type = '其他';
                        break;
                    default:
                        $type = '未知';
                        break;
                }
                $str .= "<tr><th>
                                    " . $e['startime'] . "-" . $e['endtime'] . ":
                                </th>
                                <td>
                                    ".$type."-".$e['school'] . "
                                </td></tr>";
            }
            $str = "<section><table>" . $str . "</table></section>";
            return $str;
        } else {
            $str = "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
            return $str;
        }
    }

    /**
     * 添加工作信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $work
     */
    private function addWork($work)
    {
        $conditions = array(
            'userid'      => $_SESSION['Fei_Userid'],
            'company'     => $this->FeiArgs('company'),
            'position'    => $this->FeiArgs('position'),
            'startime'    => $this->FeiArgs('startime'),
            'endtime'     => $this->FeiArgs('endtime'),
            'description' => $this->FeiArgs('description')
        );
        $this->__check_istrue($work->create($conditions));
    }


    /**
     * 异步请求工作信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $work
     */
    private function loadWork($work)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $works      = $work->findAll($conditions);
        if (count($works) != 0) {
            $str = "";
            foreach ($works as $w) {
                $str .= "<tr><th>
                                    " . $w['startime'] . "-" . $w['endtime'] . ":
                                </th>
                                <td>
                                    " . $w['company'] . "
                                </td></tr>";
            }
            $str = "<section><table>" . $str . "</table></section>";
            return $str;
        } else {
            $str = "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
            return $str;
        }
    }

    /**
     * 异步请求信息管理界面
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $work
     * @param $education
     * @param $skill
     * @param $follow
     */
    private function loadManageWork($work, $education, $skill, $follow)
    {
        $type = $this->FeiArgs('type');
        switch ($type) {
            case 'work':
                /*异步请求工作管理界面*/
                $works = $this -> loadWorkToManage($work);
                echo $works;
                exit;
                break;
            case 'education':
                /*异步请求教育经历管理界面*/
                $educations = $this -> loadWorkToEducation($education);
                echo $educations;
                exit;
                break;
            case 'skill':
                /*异步请求技能管理界面*/
                $skills = $this -> loadWorkToSkill($skill);
                echo $skills;
                exit;
                break;
            case 'follow':
                /*异步请求社交关注管理界面*/
                $follows = $this -> loadWorkToFollow($follow);
                echo $follows;
                exit;
                break;
            default:
                # code...
                break;
        }
    }

    /**
     * 异步请求工作管理信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $work
     */
    private function loadWorkToManage($work)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $works      = $work->findAll($conditions);
        $str       = "<table class=\"styled\">
                            <thead><tr>
                            <th>时间</th>
                            <th>公司名称</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";

        foreach ($works as $work) {
            $str .= "<tr>
                                <td>" . date('Y/m', strtotime($work['startime'])) . "-" . date('Y/m', strtotime($work['endtime'])) . "</td>
                                <td>" . $work['company'] . "</td>
                                <td class=\"center\">
                                    <a class=\"button small grey\" title=\"编辑\">
                                        <i class=\"icon-pencil\"></i>
                                    </a>
                                    <a class=\"button small grey\" onclick=\"_Del('work','" . $work['id'] . "')\" title=\"删除\">
                                        <i class=\"icon-remove\"></i>
                                    </a>
                                </td>
                                </tr>";
        }
        $str .= "</tbody></table>";
        return $str;
    }

    /**
     * 异步请求教育管理信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $education
     */
    private function loadWorkToEducation($education)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $educations = $education->findAll($conditions);
        $str       = "<table class=\"styled\">
                            <thead><tr>
                            <th>时间</th>
                            <th>学校</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";
        foreach ($educations as $education) {
            $str .= "<tr>
                                <td>" . date('Y/m', strtotime($education['startime'])) . "-" . date('Y/m', strtotime($education['endtime'])) . "</td>
                                <td>" . $education['school'] . "</td>
                                <td class=\"center\">
                                    <a class=\"button small grey\" title=\"编辑\">
                                        <i class=\"icon-pencil\"></i>
                                    </a>
                                    <a class=\"button small grey\" onclick=\"_Del('education','" . $education['id'] . "')\" title=\"删除\">
                                        <i class=\"icon-remove\"></i>
                                    </a>
                                </td>
                                </tr>";
        }
        $str .= "</tbody></table>";
        return $str;
    }

    /**
     * 异步请求技能管理信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $skill
     */
    private function loadWorkToSkill($skill)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $skills     = $skill->findAll($conditions);
        $str       = "<table class=\"styled\">
                            <thead><tr>
                            <th>技能特长</th>
                            <th>等级</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";
        foreach ($skills as $skill) {
            $str .= "<tr>
                                <td>" . $skill['name'] . "</td>
                                <td>" . $skill['level'] . "</td>
                                <td>" . $skill['time'] . "</td>
                                <td class=\"center\">
                                    <a class=\"button small grey\" title=\"编辑\">
                                        <i class=\"icon-pencil\"></i>
                                    </a>
                                    <a class=\"button small grey\" onclick=\"_Del('skill','" . $skill['id'] . "')\" title=\"删除\">
                                        <i class=\"icon-remove\"></i>
                                    </a>
                                </td>
                                </tr>";
        }
        $str .= "</tbody></table>";
        return $str;
    }

    /**
     * 异步请求社交关注界面
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $follow
     */
    private function loadWorkToFollow($follow)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $follows    = $follow->findAll($conditions);
        $str       = "<table class=\"styled\">
                            <thead><tr>
                            <th>图标</th>
                            <th>链接</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";
        foreach ($follows as $follow) {
            $str .= "<tr>
                                <td>" . $follow['icon'] . "</td>
                                <td>" . $follow['link'] . "</td>
                                <td class=\"center\">
                                    <a class=\"button small grey\" title=\"编辑\">
                                        <i class=\"icon-pencil\"></i>
                                    </a>
                                    <a class=\"button small grey\" onclick=\"_Del('follow','" . $follow['id'] . "')\" title=\"删除\">
                                        <i class=\"icon-remove\"></i>
                                    </a>
                                </td>
                                </tr>";
        }
        $str .= "</tbody></table>";
        return $str;
    }
    /**
     * 添加技能信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $skill
     */
    private function addSkill($skill)
    {
        $conditions = array(
            'userid' => $_SESSION['Fei_Userid'],
            'name'   => $this->FeiArgs('name'),
            'level'  => $this->FeiArgs('level'),
            'time'   => $this->FeiArgs('time')
        );
        $this->__check_istrue($skill->create($conditions));
    }

    /**
     * 异步请求技能信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $skill
     */
    private function loadSkill($skill)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $skills     = $skill->findAll($conditions);
        if (count($skills) != 0) {
            $str = "";
            foreach ($skills as $w) {
                $str .= "<tr><th>
                                    " . $w['name'] . ":
                                </th>
                                <td>
                                    " . $w['level'] . "
                                </td>
                                <td>
                                    " . $w['time'] . "
                                </td></tr>";
            }
            $str = "<section><table>" . $str . "</table></section>";
            return $str;
        } else {
            $str = "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
            return $str;
        }
    }

    /**
     * 添加社交信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $follow
     */
    private function addFollow($follow)
    {
        $conditions = array(
            'userid' => $_SESSION['Fei_Userid'],
            'icon'   => $this->FeiArgs('icon'),
            'link'   => $this->FeiArgs('link')
        );
        $this->__check_istrue($follow->create($conditions));
    }
    /**
     * 异步请求社交关注
     * @author DongYuxiang(dongm2ez@163.com)
     * @date   2014.10.21
     * @param $follow
     */
    private function loadFollow($follow)
    {
        $conditions = array('userid' => $_SESSION['Fei_Userid']);
        $follows    = $follow->findAll($conditions);
        if (count($follows) != 0) {
            $str = "";
            foreach ($follows as $w) {
                $str .= "<tr><th>
                                    " . $w['icon'] . ":
                                </th>
                                <td>
                                    " . $w['link'] . "
                                </td></tr>";
            }
            $str = "<section><table>" . $str . "</table></section>";
            return $str;
        } else {
            $str = "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
            return $str;
        }
    }

    /**
     * 删除信息
     * @author DongYuxiang(dongm2ez@163.com)
     * @Date 2014.10.23
     * @param $work
     * @param $education
     * @param $skill
     * @param $follow
     */
    private function delete($work, $education, $skill, $follow)
    {
        $type = $this->FeiArgs('type');
        $id   = $this->FeiArgs('id');
        switch ($type) {
            case 'work':
                $this->__check_istrue($work->deleteByPk($id), '工作经历');
                break;
            case 'education':
                $this->__check_istrue($education->deleteByPk($id), '学习经历');
                break;
            case 'skill':
                $this->__check_istrue($skill->deleteByPk($id), '技能特长');
                break;
            case 'follow':
                $this->__check_istrue($follow->deleteByPk($id), '社交关注');
                break;
            default:
                $result = array('status' => 'error');
                echo json_encode($result);
                exit;
                break;
        }
    }

}