<?php

/**
 * 个人信息
 * Class profile
 */
class profile extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('profile');
	}

	function index()
	{
		$user      = FeiClass('model_user');
		$education = FeiClass('model_education');
		$work      = FeiClass('model_work');
		$skill     = FeiClass('model_skill');
		$follow    = FeiClass('model_follow');
		if (isset($_POST['action']) && $_POST['action'] == 'edit_profile') { //Edit Profile
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
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_education') { //Add Education
			$conditions = array(
				'userid'   => $_SESSION['Fei_Userid'],
				'school'   => $this->FeiArgs('school'),
				'type'     => $this->FeiArgs('type'),
				'startime' => $this->FeiArgs('startime'),
				'endtime'  => $this->FeiArgs('endtime')
			);
			$this->__check_istrue($education->create($conditions));
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_education_info') { //Ajax Load Education Info
			$conditions = array('userid' => $_SESSION['Fei_Userid']);
			$e_list     = $education->findAll($conditions);
			if (count($e_list) != 0) {
				foreach ($e_list as $e) {
					$str .= "<tr><th>
                                    " . $e['startime'] . "-" . $e['endtime'] . ":
                                </th>
                                <td>
                                    " . $e['school'] . "
                                </td></tr>";
				}
				echo "<section><table>" . $str . "</table></section>";
				exit;
			} else {
				echo "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
				exit;
			}
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_work') { //Add Work
			$conditions = array(
				'userid'      => $_SESSION['Fei_Userid'],
				'company'     => $this->FeiArgs('company'),
				'position'    => $this->FeiArgs('position'),
				'startime'    => $this->FeiArgs('startime'),
				'endtime'     => $this->FeiArgs('endtime'),
				'description' => $this->FeiArgs('description')
			);
			$this->__check_istrue($work->create($conditions));
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_work_info') { //Ajax Load Work Info
			$conditions = array('userid' => $_SESSION['Fei_Userid']);
			$works      = $work->findAll($conditions);
			if (count($works) != 0) {
				foreach ($works as $w) {
					$str .= "<tr><th>
                                    " . $w['startime'] . "-" . $w['endtime'] . ":
                                </th>
                                <td>
                                    " . $w['company'] . "
                                </td></tr>";
				}
				echo "<section><table>" . $str . "</table></section>";
				exit;
			} else {
				echo "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
				exit;
			}
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_manage') { //Get Manage Work
			$type = $this->FeiArgs('type');
			switch ($type) {
				case 'work':
					$conditions = array('userid' => $_SESSION['Fei_Userid']);
					$works      = $work->findAll($conditions);
					$str1       = "<table class=\"styled\">
                            <thead><tr>
                            <th>时间</th>
                            <th>公司名称</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";
					foreach ($works as $work) {
						$str2 .= "<tr>
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
					$str3 = "</tbody></table>";
					echo $str1, $str2, $str3;
					exit;
					break;
				case 'education':
					$conditions = array('userid' => $_SESSION['Fei_Userid']);
					$educations = $education->findAll($conditions);
					$str1       = "<table class=\"styled\">
                            <thead><tr>
                            <th>时间</th>
                            <th>学校</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";
					foreach ($educations as $education) {
						$str2 .= "<tr>
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
					$str3 = "</tbody></table>";
					echo $str1, $str2, $str3;
					exit;
					break;
				case 'skill':
					$conditions = array('userid' => $_SESSION['Fei_Userid']);
					$skills     = $skill->findAll($conditions);
					$str1       = "<table class=\"styled\">
                            <thead><tr>
                            <th>技能特长</th>
                            <th>等级</th>
                            <th>时间</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";
					foreach ($skills as $skill) {
						$str2 .= "<tr>
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
					$str3 = "</tbody></table>";
					echo $str1, $str2, $str3;
					exit;
					break;
				case 'follow':
					$conditions = array('userid' => $_SESSION['Fei_Userid']);
					$follows    = $follow->findAll($conditions);
					$str1       = "<table class=\"styled\">
                            <thead><tr>
                            <th>图标</th>
                            <th>链接</th>
                            <th>操作</th>
                        </tr></thead>
                        <tbody>";
					foreach ($follows as $follow) {
						$str2 .= "<tr>
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
					$str3 = "</tbody></table>";
					echo $str1, $str2, $str3;
					exit;
					break;
				default:
					# code...
					break;
			}
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_skill') { //Add Skill
			$conditions = array(
				'userid' => $_SESSION['Fei_Userid'],
				'name'   => $this->FeiArgs('name'),
				'level'  => $this->FeiArgs('level'),
				'time'   => $this->FeiArgs('time')
			);
			$this->__check_istrue($skill->create($conditions));
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_skill_info') { //Ajax Load Skill Info
			$conditions = array('userid' => $_SESSION['Fei_Userid']);
			$skills     = $skill->findAll($conditions);
			if (count($skills) != 0) {
				foreach ($skills as $w) {
					$str .= "<tr><th>
                                    " . $w['name'] . ":
                                </th>
                                <td>
                                    " . $w['time'] . "
                                </td></tr>";
				}
				echo "<section><table>" . $str . "</table></section>";
				exit;
			} else {
				echo "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
				exit;
			}
		} elseif (isset($_POST['action']) && $_POST['action'] == 'add_follow') { //Add Follow
			$conditions = array(
				'userid' => $_SESSION['Fei_Userid'],
				'icon'   => $this->FeiArgs('icon'),
				'link'   => $this->FeiArgs('link')
			);
			$this->__check_istrue($follow->create($conditions));
		} elseif (isset($_POST['action']) && $_POST['action'] == 'get_follow_info') { //Ajax Load Follow Info
			$conditions = array('userid' => $_SESSION['Fei_Userid']);
			$follows    = $follow->findAll($conditions);
			if (count($follows) != 0) {
				foreach ($follows as $w) {
					$str .= "<tr><th>
                                    " . $w['icon'] . ":
                                </th>
                                <td>
                                    " . $w['link'] . "
                                </td></tr>";
				}
				echo "<section><table>" . $str . "</table></section>";
				exit;
			} else {
				echo "<section class=\"center-elements\"><p><i>还未添加数据，请点击右上角添加！</i></p></section>";
				exit;
			}
		} elseif (isset($_POST['action']) && $_POST['action'] == 'del_type') {
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
		} else {
			//User Info
			$conditions = array('id' => $_SESSION['Fei_Userid']);
			$this->user = $user->find($conditions);
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

	}
}