<?php

class setting extends Grw
{

	function __construct()
	{
		parent::__construct();
		$this->__initial('setting');
	}

	function index()
	{

	}

	function menu()
	{
		$this->arr = $this->__get_categorys(0);
	}

	function ajax_databack()
	{
		if ($_POST['handle'] == 'backup') {
			import(APP_PATH . "/lib/Backup.php", FALSE, TRUE);
			$link = @mysql_connect("localhost", "root", "133285") or die ('Could not connect to server.');
			mysql_query("use feitm", $link);
			mysql_query("set names utf8", $link);
			$dbbck = new backupData($link); //实例化它，只要一个链接标识就行了
			//备份数据时，如想备份一个数据库中的所有表，你可这样写：

			$dbbck->backupTables("feitm", "./backup/", array('*'));
			echo 'success';
			//备份数据时，如想备份一个数据库中的仅一个表时，你可这样写：
			// $dbbck->backupTables("cms","./",array('user'));
			//备份数据时，如想备份一个数据库中的多个表时，你可这样写：
			// $dbbck->backupTables("cms","./",array('user','acl','informatoin'));
			//注解：$dbbck->backupTables("参1","参2",array());中，
		} else {
			$this->filelists = $this->__filelists();
		}
	}

	function databack()
	{
		$this->filelists = $this->__filelists();
	}

	function __filelists()
	{
		$dir  = array();
		$dirs = array();
		foreach (glob("./backup/*.sql") as $filename) {
			$dir[] = $filename;
		}
		for ($i = 0; $i < count($dir); $i++) {
			$dirs[$i]['filename'] = end(explode('/', $dir[$i]));
			$size                 = filesize($dir[$i]);
			if ($size < 1024) {
				$dirs[$i]['filesize'] = filesize($size);
			} elseif ($size < 1048576) {
				$dirs[$i]['filesize'] = round($size / 1024, 2) . 'K';
			} elseif ($size < 1073741824) {
				$dirs[$i]['filesize'] = round($size / 1048576, 2) . 'M';
			} elseif ($size < 1099511627776) {
				$dirs[$i]['filesize'] = round($size / 1073741824, 2) . 'G';
			}
			$dirs[$i]['filectime'] = date('Y-m-d h:m:s', filectime($dir[$i]));
		}
		return $dirs;
	}


	//权限设置
	function permission()
	{
		$acl = FeiClass(model_acl);
		if ($_POST['action'] == 'edit' && isset($_POST['aclid']) && !empty($_POST['aclid']) && !isset($_POST['from'])) {
			$this->P_s = $acl->findBy('aclid', $_POST['aclid']);
		} else if ($_POST['action'] == 'edit' && $_POST['from'] == 'page') { //编辑
			$condition = array('aclid' => $this->FeiArgs('aclid'));
			$rows      = array(
				'name'       => $this->FeiArgs('name'),
				'controller' => $this->FeiArgs('controller'),
				'action'     => $this->FeiArgs('act'),
				'acl_name'   => $this->FeiArgs('acl')
			);
			if ($acl->update($condition, $rows)) {
				$this->success('修改成功！', FeiUrl('setting', 'permission'));
			} else {
				$this->error('错误', FeiUrl('setting', 'permission'));
			}
		} else if (isset($_POST['acl_action']) && $_POST['acl_action'] == 'add') { //添加
			$rows = array(
				'name'       => $this->FeiArgs('name'),
				'controller' => $this->FeiArgs('controller'),
				'action'     => $this->FeiArgs('action'),
				'acl_name'   => $this->FeiArgs('acl_name')
			);
			if ($acl->create($rows)) {
				echo 'success';
				exit;
			} else {
				echo 'error';
				exit;
			}
		} else if (isset($_POST[action]) && $_POST[action] == 'del') { //删除
			//TODO:validate is for this user
			$aclid      = $this->FeiArgs('aclid');
			$conditions = array('aclid' => $aclid);
			if ($acl->delete($conditions)) {
				echo 'success';
				exit;
			} else {
				echo 'error';
				exit;
			}
		}
		$this->result = FeiClass(model_acl)->findAll();
	}

	//栏目管理
	function category()
	{
		$category  = FeiClass(model_category);
		$this->arr = $this->__get_categorys(0);
		if (isset($_GET['catid'])) {
			$this->result = $category->findBy('catid', $_GET['catid']);
		} else if (isset($_POST[action]) && $_POST['action'] == 'edit_category') { //Edit Category
			$g_cate       = $this->FeiArgs();
			$condititions = array('catid' => $g_cate['catid']);
			$rows         = array(
				'parentid'  => $g_cate['parentid'],
				'catname'   => $g_cate['catname'],
				'listorder' => $g_cate['listorder'],
				'ismenu'    => $g_cate['ismenu'],
				'letter'    => $g_cate['letter'],
				'ico'       => $g_cate['ico']
			);
			if ($category->update($condititions, $rows)) {
				echo 'success';
				exit;
			} else {
				echo 'error';
				exit;
			}
		} else if (isset($_POST[action]) && $_POST[action] == 'add_category') { //Add Category
			$a_cate = $this->FeiArgs();
			$rows   = array(
				'parentid'  => $a_cate['parentid'],
				'catname'   => $a_cate['catname'],
				'listorder' => $a_cate['listorder'],
				'ismenu'    => $a_cate['ismenu'],
				'letter'    => $a_cate['letter'],
				'ico'       => $a_cate['ico']
			);
			if ($category->create($rows)) {
				echo 'success';
				exit;
			} else {
				echo 'error';
				exit;
			}
		} else if (isset($_POST[action]) && $_POST[action] == 'del_category') { //Del Category
			//TODO:validate is for this user
			$catid      = $this->FeiArgs('catid');
			$category   = FeiClass('model_category');
			$conditions = array('catid' => $catid);
			if ($category->delete($conditions)) {
				echo 'success';
				exit;
			} else {
				echo 'error';
				exit;
			}
		} else if (isset($_POST['action']) && $_POST['action'] == 'get_category_content') { //Ajax Get Category Content
			$arr = $this->__get_categorys(0);
			//dump($arr);exit;
			foreach ($arr as $v) {
				echo "<tr>
                    <td>
                        <img src=\"" . $this->STATICS . "img/icons/packs/fugue/16x16/" . $v['ico'] . ".png\" alt=\"\" height=\"16\" width=\"16\">
                    </td>
                    <td>" . $v['catid'] . "</td>
                    <td>" . $v['catname'] . "</td>
                    <td>" . $v['letter'] . "</td>
                    <td class=\"center\">" . $v['listorder'] . "</td>
                    <td class=\"center\">" . $v['ismenu'] . "</td>
                    <td class=\"center\" >
                        <a href=\"javascript:void(0);\" onclick=\"Edit_Category(" . $v['catid'] . ")\" class=\"button small grey tooltip\" data-gravity=\"s\" original-title=\"编辑\">
                            <i class=\"icon-pencil\"></i>
                        </a>
                        <a href=\"javascript:void(0);\" onclick=\"Delete_Category(" . $v['catid'] . ")\" class=\"button small grey tooltip\" data-gravity=\"s\" original-title=\"删除\">
                            <i class=\"icon-remove\"></i>
                        </a>
                    </td>
                </tr>";

				foreach ($v['cate2'] as $c) {
					echo "<tr>
                        <td></td>
                        <td>" . $c['catid'] . "</td>
                        <td>||--—<span class=\"" . $c['ico'] . "\"></span> " . $c['catname'] . "</td>
                        <td>" . $c['letter'] . "</td>
                        <td class=\"center\">" . $c['listorder'] . "</td>
                        <td class=\"center\">" . $c['ismenu'] . "</td>
                        <td class=\"center\" >
                            <a href=\"javascript:void(0);\" onclick=\"Edit_Category(" . $c['catid'] . ")\" class=\"button small grey tooltip\" data-gravity=\"s\" original-title=\"编辑\">
                                <i class=\"icon-pencil\"></i>
                            </a>
                            <a href=\"javascript:void(0);\" onclick=\"Delete_Category(" . $c['catid'] . ")\" class=\"button small grey tooltip\" data-gravity=\"s\" original-title=\"删除\">
                                <i class=\"icon-remove\"></i>
                            </a>
                        </td>
                    </tr>";
				}
			}
			exit;
		} else if (isset($_POST['action']) && $_POST['action'] == 'get_edit_category_content') { //Get Edit Category Content
			$catid      = $this->FeiArgs('catid');
			$conditions = array(
				'catid' => $catid
			);
			$cat_info   = $category->find($conditions);
			echo "<form id=\"edit_category\" class=\"full validate\">
                    <div class=\"row\">
                        <label for=\"edit_catename\">
                            <strong>
                                名称&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </strong>
                        </label>
                        <div class=\"_75 input\">
                            <input class=\"required\" type=\"text\" name=\"edit_catename\" value=\"" . $cat_info['catname'] . "\" />
                        </div>
                    </div>
                    <div class=\"row\">
                        <label for=\"edit_letter\">
                            <strong>
                                英文名称
                            </strong>
                        </label>
                        <div class=\"_75 input\">
                            <input class=\"required stringEN\" type=text name=edit_letter value=\"" . $cat_info['letter'] . "\" />
                        </div>
                    </div>
                    <div class=\"row\">
                        <label for=\"edit_parentid\">
                            <strong>
                                所属栏目
                            </strong>
                        </label>
                        <div class=\"_75 input\">
                            <select name=\"edit_parentid\" class=\"search\" data-placeholder=\"请选择所属栏目\">
                                <option value=\"0\">
                                    作为一级栏目
                                </option>
                                " . $this->__get_category($cat_info['parentid']) . "
                            </select>
                        </div>
                    </div>
                    <div class=\"row\">
                        <label for=\"edit_ico\">
                            <strong>
                                图标&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </strong>
                        </label>
                        <div class=\"_75 input\">
                            <p class=\"_25\">
                            <input class=\"required stringEN\" type=text name=edit_ico value=\"" . $cat_info['ico'] . "\" />
                            </p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <label for=\"edit_listorder\">
                            <strong>
                                排序&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            </strong>
                        </label>
                        <div class=\"_75 input\">
                            <p class=\"_25\">
                            <input data-type=\"spinner\" min=\"0\" max=\"20\" value=\"" . $cat_info['listorder'] . "\"  name=\"edit_listorder\" class=\"ui-spinner-input required\" autocomplete=\"off\">
                            </p>
                        </div>
                    </div>
                    <div class=\"row\">
                        <label for=\"edit_ismenu\">
                            <strong>
                                是否显示
                            </strong>
                        </label>
                        <div class=\"_75 input\">
                            <input type=\"radio\" name=\"edit_ismenu\" value=\"1\" " . $this->__check_equally_checked(1, $cat_info['ismenu']) . " style=\"width:13px\">是
                            <input type=\"radio\" name=\"edit_ismenu\" value=\"0\" " . $this->__check_equally_checked(0, $cat_info['ismenu']) . " style=\"width:13px\">否
                        </div>
                    </div>
                </form>
                <div class=\"actions\">
                    <div class=\"left\">
                        <button class=\"grey cancel\">
                            取消
                        </button>
                    </div>
                    <div class=\"right\">
                        <button class=\"submit\">
                            修改
                        </button>
                    </div>
                </div>";
			exit;
		}
		//$this->arr =  $this->__get_categorys(0);
	}

	function __get_category($catid)
	{
		$category = FeiClass(model_category);
		$arr      = $category->findAll(array("parentid" => 0), 'listorder');
		if ($catid == 0) {
			$str1 = "<option value=\"0\" selected>作为一级栏目</option>";
		}
//            foreach($arr as $k=>$v){
//                $arr[$k]['c2'] = $this->__get_categorys($v[catid]);
//            }
		foreach ($arr as $cate) {
			$str2 .= "<option value=\"" . $cate['catid'] . "\" " . $this->__check_equally($catid, $cate['catid']) . ">" . $cate['catname'] . "</option>";
//                foreach($cate['c2'] as $cate2){
//                    $str .= "<option value=\"".$cate2['catid']."\">||-——-".$cate2['catname']."</option>";
//                }
		}
		return $str1 . $str2;
	}

	function __check_equally($n, $c)
	{
		if ($n == $c) {
			return 'selected';
		}
	}

	function __check_equally_checked($n, $c)
	{
		if ($n == $c) {
			return 'checked';
		}
	}

	function __get_categorys($parentid = 0)
	{
		$category = FeiClass(model_category);
		$arr      = $category->findAll(array("parentid" => $parentid), 'listorder');
		foreach ($arr as $k => $v) {
			$arr[$k]['cate2'] = $this->__get_categorys($v[catid]);
		}
		return $arr;
	}

	function module()
	{
		$action   = $this->FeiArgs('action', NULL, 'POST');
		$data     = $this->FeiArgs(NULL, NULL, 'POST');
		$category = FeiClass('model_category');
		switch ($action) {
			case 'get_modules':
				$categorys = $category->topcategory();
				for ($i = 0; $i < count($categorys); $i++) {
					if ($categorys[$i]['ismenu'] == 1) {
						$ismenu = "<input type=\"checkbox\" checked=\"checked\" name=\"ismenu_" . $categorys[$i]['catid'] . "\"> 已开启";
					} else if ($categorys[$i]['ismenu'] == 0) {
						$ismenu = "<input type=\"checkbox\"  name=\"ismenu_" . $categorys[$i]['catid'] . "\"> 未开通";
					}
					$html .= "
                        <tr>
                            <td>" . $i . "</td>
                            <td>
                            <img src=\"" . $this->STATICS . "img/icons/packs/fugue/16x16/" . $categorys[$i]['ico'] . ".png\" alt=" . $categorys[$i]['catname'] . " height=16 width=16>
                            " . $categorys[$i]['catname'] . "
                            </td>
                            <td>" . $categorys[$i]['remark'] . "</td>
                            <td align=\"center\">
                                " . $ismenu . "
                            </td>
                        </tr>";
				}
				echo $html;
				exit;
			default:
				# code...
				break;
		}
	}

}