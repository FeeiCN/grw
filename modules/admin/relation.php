<?php

/**
 * 人际关系
 * Class relation
 */
class relation extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('relation');
	}

	function contacter()
	{
		$contact = FeiClass('model_relation_contacter');
		$about   = FeiClass('model_relation_about');
		$action  = $this->FeiArgs('action', NULL, 'POST');
		$data    = $this->FeiArgs(NULL, NULL, 'POST');
		switch ($action) {
			case 'add_contacter':
				if ($data['birthtype'] == 0) {
					$lunar            = FeiClass('FeiLunar');
					$m                = date('m', $lunar->S2L($data['birtyday']));
					$d                = date('d', $lunar->S2L($data['birthday']));
					$data['birthday'] = date('Y', strtotime($data['birthday'])) . '-' . $m . '-' . $d;
				}
				//Get firstchar
				$data['firstchar'] = strtolower($this->__Get_firstchar($data['name']));
				$this->__check_istrue($id = $contact->add($data), $id);
			case 'getAll':
				$cs = $contact->getAll();
				for ($i = 0; $i < count($cs); $i++) {
					if ($cs[$i]['avatar'] == 0) {
						$avatar = $this->STATICS . "images/avatar2.png";
					} else {
						$avatar = $this->PIC . $_SESSION['Fei_Userid'] . "mb.png";
					}
					//lunar
					if ($cs[$i]['birthtype'] == 0) {
						$lunar = FeiClass('FeiLunar');
						$m     = date('m', strtotime($cs[$i]['birthday']));
						$d     = date('d', strtotime($cs[$i]['birthday']));

					}
				}
				$char = array("a", "b", "c", "d", "e", "f", "g", "h", "i", "j", "k", "l", "m", "n", "o", "p", "q", "r", "s", "t", "u", "v", "w", "x", "y", "z");
				for ($j = 0; $j < count($char); $j++) {
					$result = $contact->getchar($char[$j]);
					if (count($result) == 0) {
						$lis = "<li></li>";
					} else {
						unset($lis);
						for ($k = 0; $k < count($result); $k++) {
							$lis .= "<li><a onclick=\"Contacter.getOne(" . $result[$k]['id'] . ")\">" . $result[$k]['name'] . "</a></li>";
						}
					}
					unset($ws);
					if (count($result) != 0) {
						$ws = '(' . count($result) . '位)';
					}
					$html .= "
                        <li id=\"" . $char[$j] . "\"><a name=\"" . $char[$j] . "\" class=\"title\">" . $char[$j] . $ws . "</a>
                             <ul>
                                 " . $lis . "
                             </ul>
                         </li>";
				}

				echo '<ul>' . $html . '</ul>';
				exit;
			case 'getOne':
				$result = $contact->getOne($data);
				if ($result['birthday'] == '1970-01-01') {
					$result['birthday'] = '未填写';
				} else {
					if ($result['birthtype'] == 0) {
						$lunar              = FeiClass('FeiLunar');
						$d                  = date('d', strtotime($result['birthday']));
						$m                  = date('m', strtotime($result['birthday']));
						$y                  = date('y', strtotime($result['birthday']));
						$y                  = $lunar->LYearName($y);
						$m                  = $lunar->LMonName(intval($m));
						$d                  = $lunar->LDayName(intval($d));
						$result['birthday'] = $y . '年' . $m . '月' . $d . '(农历)';
					} else if ($result['birthtype'] == 1) {
						$result['birthday'] .= '(公历)';
					}
				}
				if ($result['qq'] == 0) $result['qq'] = '未填写';
				if ($result['mobile'] == 0) $result['mobile'] = '未填写';
				if ($result['weibo'] == 0) $result['weibo'] = '未填写';
				if ($result['email'] == 0) $result['email'] = '未填写';
				if ($result['infantname'] != NULL) $result['infantname'] = "(" . $result['infantname'] . ")";
				$html .= "
                        <a class=\"open-profile-dialog\" onclick=\"Contacter.del(" . $result['id'] . ")\" href=\"javascript:void(0);\">
                            <span class=\"icon icon-pencil\"></span>
                            删除联系人
                        </a>
                        <a class=\"open-profile-dialog\" onclick=\"Contacter.edit(" . $result['id'] . ")\" href=\"javascript:void(0);\">
                            <span class=\"icon icon-pencil\"></span>
                            修改信息
                        </a>
                        <h2>基本信息</h2>
                        <section>
                            <img class=\"avatar\" src=\"http://www.grw.name/statics/images/avatar2.png\" style=\"position:initial\">
                            <table>
                                <tbody>
                                <tr>
                                    <th>姓名:</th>
                                    <td class=\"green\">" . $result['name'] . $result['infantname'] . "</td>
                                </tr>
                                <tr>
                                    <th>户籍:</th>
                                    <td>" . $result['birthplace'] . "</td>
                                </tr>
                                <tr>
                                    <th>生日:</th>
                                    <td>" . $result['birthday'] . "</td>
                                </tr>
                                <tr>
                                    <th> QQ:</th>
                                    <td>" . $result['qq'] . "</td>
                                </tr>
                                <tr>
                                    <th>手机:</th>
                                    <td>" . $result['mobile'] . "</td>
                                </tr>
                                <tr>
                                    <th>邮箱:</th>
                                    <td>" . $result['email'] . "</td>
                                </tr>
                                </tbody>
                            </table>
                        </section>
                ";
				echo $html;
				exit;
			case 'getodo':
				$abouts = $about->getAll($data['id']);
				for ($i = 0; $i < count($abouts); $i++) {
					$html .= "<tr>
                                <th>" . $abouts[$i]['name'] . "</th>
                                <td>
                                    <i>" . $abouts[$i]['value'] . "</i>
                                </td>
                            </tr>";
				}
				if (count($abouts) == 0) $html = "<tr><th>暂无</th></tr>";
				echo "
                        <h2>相关事项</h2>
                        <a onclick=\"Contacter.open_aa(" . $data['id'] . ")\" href=\"#\">
                            <span class=\"icon icon-plus\"></span>
                            添加事项
                        </a>
                        <section>
                            <table>
                                <tbody>
                                " . $html . "
                                </tbody>
                            </table>
                        </section>
                ";
				exit;
			case 'info':
				$this->__check_istrue($info = $contact->info($data), $info);
			case 'edit_contacter':
				//Get firstchar
				$data['firstchar'] = strtolower($this->__Get_firstchar($data['name']));
				$this->__check_istrue($contact->edit($data), $data['cid']);
			case 'del_contacter':
				$this->__check_istrue($contact->del($data));
			case 'add_about':
				$this->__check_istrue($about->add($data));
			default:
				$cs              = $contact->getAll();
				$this->countUser = count($cs);
				break;
		}
	}

	/**
	 * 获取首字母
	 * @param $s0
	 * @return null|string
	 */
	function __Get_firstchar($s0)
	{
		$fchar = ord($s0{0});
		if ($fchar >= ord("A") and $fchar <= ord("z")) return strtoupper($s0{0});
		$s1 = iconv("UTF-8", "gb2312", $s0);
		$s2 = iconv("gb2312", "UTF-8", $s1);
		if ($s2 == $s0) {
			$s = $s1;
		} else {
			$s = $s0;
		}
		$asc = ord($s{0}) * 256 + ord($s{1}) - 65536;
		if ($asc >= -20319 and $asc <= -20284) return "A";
		if ($asc >= -20283 and $asc <= -19776) return "B";
		if ($asc >= -19775 and $asc <= -19219) return "C";
		if ($asc >= -19218 and $asc <= -18711) return "D";
		if ($asc >= -18710 and $asc <= -18527) return "E";
		if ($asc >= -18526 and $asc <= -18240) return "F";
		if ($asc >= -18239 and $asc <= -17923) return "G";
		if ($asc >= -17922 and $asc <= -17418) return "H";
		if ($asc >= -17417 and $asc <= -16475) return "J";
		if ($asc >= -16474 and $asc <= -16213) return "K";
		if ($asc >= -16212 and $asc <= -15641) return "L";
		if ($asc >= -15640 and $asc <= -15166) return "M";
		if ($asc >= -15165 and $asc <= -14923) return "N";
		if ($asc >= -14922 and $asc <= -14915) return "O";
		if ($asc >= -14914 and $asc <= -14631) return "P";
		if ($asc >= -14630 and $asc <= -14150) return "Q";
		if ($asc >= -14149 and $asc <= -14091) return "R";
		if ($asc >= -14090 and $asc <= -13319) return "S";
		if ($asc >= -13318 and $asc <= -12839) return "T";
		if ($asc >= -12838 and $asc <= -12557) return "W";
		if ($asc >= -12556 and $asc <= -11848) return "X";
		if ($asc >= -11847 and $asc <= -11056) return "Y";
		if ($asc >= -11055 and $asc <= -10247) return "Z";
		return NULL;
	}

}