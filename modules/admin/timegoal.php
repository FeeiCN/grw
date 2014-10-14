<?php

class timegoal extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('timegoal');
		$todo = FeiClass('model_todo');
		//Total todos
		$this->todos = array(
			'todo'  => $todo->statics(0),
			'maybe' => $todo->statics(3)
		);
	}

	/**
	 * today(今日待办)

	 */
	function today()
	{
		$todo   = FeiClass('model_todo');
		$action = $this->FeiArgs('action', NULL, 'POST');
		$data   = $this->FeiArgs(NULL, NULL, 'POST');
		//Set Cookie
		$todotime = $todo->findAll('', 'endtime ASC', '', '');
		$count    = count($todotime);
		for ($i = 0; $i < $count; $i++) {
			$time = $todotime[$i]['endtime'];
			if ($this->__istoday($time)) {
//                    $end_todo[$i]['doid'] = $todotime[$i]['doid'];
//                    $end_todo[$i]['name'] = $todotime[$i]['name'];
//                    $end_todo[$i]['remark'] = $todotime[$i]['remark'];
//                    $end_todo[$i]['h'] = intval(date('h',strtotime($todotime[$i]['endtime'])));
//                    $end_todo[$i]['i'] = date('i',strtotime($todotime[$i]['endtime']));
				$todotime[$i]['h'] = intval(date('h', strtotime($todotime[$i]['endtime'])));
				$todotime[$i]['m'] = date('i', strtotime($todotime[$i]['endtime']));
				$end_todo[]        = $todotime[$i];
				//$today_todo[$i]['name'] = $todotime[$i]['name'];
				//$today_todo[$i]['remark'] = $todotime[$i]['remark'];
				//$today_todo[$i]['endtime'] = $todotime[$i]['endtime'];
				//$today_todo[$i]['level'] = $todotime[$i]['level'];
				//$today_todo[$i]['tags'] = $todotime[$i]['tags'];
				//$today_todo[$i]['status'] = $todotime[$i]['status'];
			}
		}
		$cookie_todos = json_encode(array_slice($end_todo, 0, 2));
		if (setcookie("Fei_Todo", $cookie_todos)) {
			// 'setcookie_success';
		}
		//Tags
		$tags        = FeiClass(model_todo_tags);
		$this->tagss = $tags->getAll();

		switch ($action) {
			case 'ajax':
				if ($todo->findBy('doid', $this->FeiArgs('doid'))) {
					$todolist = $todo->findBy('doid', $this->FeiArgs('doid'));
					$data     = array(
						'status' => 'success',
						'msg'    => '读取成功！',
						'name'   => $todolist['name'],
						'remark' => $todolist['remark']
					);
					echo json_encode($data);
				} else {
					$data = array(
						'status' => 'error',
						'msg'    => '加载错误！'
					);
				}
				break;
			case 'add_todo':
				$this->__check_istrue($todo->add($data), $data['tags']);
			case 'add_todo_enter':
				$this->__check_istrue($todo->add_enter($data), 1);
			//default tags is 1
			case 'edit_todo':
				$this->__check_istrue($todo->edit($data), $data['tags']);
			case 'completed_todo':
				$this->__check_istrue($todo->completed($data), $todo->find_tags_by_todoid($data['doid']));
			case 'cancle_completed_todo':
				$this->__check_istrue($todo->cancel_completed($data));
			case 'cancle_trash_todo':
				$this->__check_istrue($todo->cancel_trash($data));
			case 'ajax_load_todo_content':
				if ($data['from'] == 'index') { //控制面板
					//@TODO:取今天并按优先级排序
					$todolist = $todo->getAll($data, 'index');
					$count    = count($todolist);
					if ($count == 0) echo '<div class="alert information top">
                                            <span class="icon">
                                            </span><span class="close">x</span>
                                            暂无数据，请至[时间目标]-[今日待办]添加！
                                        </div>';
					for ($i = 0; $i < $count; $i++) {
						$html .= '
                            <div class="msg">
                                <div class="left">
                                    <button onclick="Todo.complete(' . $todolist[$i]['doid'] . ')" id=completed_button_' . $todolist[$i]['doid'] . ' class="button grey block">
                                         <span class="icon icon-check-empty"></span>
                                                确定完成
                                         </button>
                                </div>
                                <div class="content">
                                    <h3>'
							. $todolist[$i]['name'] .
							'<div style="display: inline;float:right;">
								' . $this->__check_repeats($todolist[$i]['doid']) . '<span class="icon-time"> ' . $todolist[$i]['endtime'] . '</span>
                                        </div>
                                    </h3>
                                    <p>'
							. $todolist[$i]['remark'] .
							'</p>
						</div>
					</div>';
					}
					$html = '<div class="spacer">
                                </div>
                                <div class="messages full">' . $html .
						"</div>";
					$this->__check_istrue(TRUE, $html);
				} else { //Today Todo
					$todolist = $todo->getAll($data, 'todo');
					$count    = count($todolist);
					$tags->total($data['tagid']); //update tag total
					for ($i = 0; $i < $count; $i++) {
						$html .= "
                            <div class=\"msg\" style=\"display:block;\">
                                <div class=left>
                                    <button onclick=\"Todo.complete(" . $todolist[$i]['doid'] . ")\" id=completed_button_" . $todolist[$i]['doid'] . " class=\"button grey block\">
                                         <span class=\"icon icon-check-empty\"></span>
                                                确定完成
                                         </button>
                                         <ul class=\"buttons\">
                                              <li>
                                              <a onclick=Todo.maybe(" . $todolist[$i]['doid'] . ") title=\"移动到[将来或许]\">
                                              <img src=\"statics/img/icons/packs/iconsweets2/16x16/bended-arrow-right.png\" alt= />
                                              </a>
                                         </li>
                                        <li>
                                            <a onclick=Todo.edit(" . $todolist[$i]['doid'] . ") href=\"javascript:void(0);\" title=\"修改\">
                                                <img src=\"statics/img/icons/packs/iconsweets2/16x16/create---write.png\" />
                                            </a>
                                        </li>
                                        <li>
                                            <a href=\"javascript:void(0);\" onclick=\"Todo.del(" . $todolist[$i]['doid'] . ")\" title=\"删除\">
                                                <img src=\"statics/img/icons/packs/iconsweets2/16x16/trashcan-2.png\" />
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class=\"content\">
                                    <h3>"
							. $todolist[$i]['name'] .
							"<div style=\"display: inline;float:right;\">
								" . $this->__check_repeats($todolist[$i]['doid']) . "<span class=\"icon-time\"> " . $todolist[$i]['createtime'] . "</span>
                                        </div>
                                    </h3>
                                    <p>"
							. $todolist[$i]['remark'] .
							"</p>
						</div>
					</div>";
					}
					echo $html;
					exit;
				}
				break;
			case 'ajax_load_completed_todo_content':
				$todos = $todo->getAll($data, 'completed');
				for ($i = 0; $i < count($todos); $i++) {
					$html .= "<div class=msg>
                                <div class=left>
                                    <button onclick=\"Todo.cancel_c(" . $todos[$i]['doid'] . ")\" id=cancle_completed_button_" . $todos[$i]['doid'] . " class=\"button grey block\">
                                         <span class=\"icon icon-check-empty\"></span>
                                                取消完成
                                         </button>
                                         <ul class=\"buttons\">
                                              <li>
                                              <a class=\"open-message-dialog\" title=\"推后一点\">
                                              <img src=\"statics/img/icons/packs/iconsweets2/16x16/bended-arrow-right.png\" alt= />
                                              </a>
                                         </li>
                                        <li>
                                            <a onclick=Todo.edit(" . $todos[$i]['doid'] . ") href=\"javascript:void(0);\" title=\"修改\">
                                                <img src=\"statics/img/icons/packs/iconsweets2/16x16/create---write.png\" />
                                            </a>
                                        </li>
                                        <li>
                                            <a href=\"javascript:void(0);\" onclick=\"Todo.del(" . $todos[$i]['doid'] . ")\" title=\"删除\">
                                                <img src=\"statics/img/icons/packs/iconsweets2/16x16/trashcan-2.png\" />
                                            </a>
                                        </li>
                                    </ul>
                                    </div>
                                    <div class=\"content\">
                                        <h3>"
						. $todos[$i]['name'] .
						"<div style=\"display: inline;float:right;\">
							<span class=\"icon-time\">" . $todos[$i]['endtime'] . "</span>
                                            </div>
                                        </h3>
                                        <p>"
						. $todos[$i]['remark'] .
						"</p>
					</div>
				</div>";
				}
				echo $html;
				exit;
			case 'ajax_load_trash_todo_content':
				$todos = $todo->getAll($data, 'trash');
				for ($i = 0; $i < count($todos); $i++) {
					$html .= "<div class=msg>
                                <div class=left>
                                    <button onclick=\"Todo.cancel_t(" . $todos[$i]['doid'] . ")\" id=cancle_trash_button_" . $todos[$i]['doid'] . " class=\"button grey block\">
                                         <span class=\"icon icon-check-empty\"></span>
                                                恢复待办
                                         </button>
                                </div>
                                <div class=\"content\">
                                    <h3>"
						. $todos[$i]['name'] .
						"<div style=\"display: inline;float:right;\">
							<span class=\"icon-time\">" . $todos[$i]['endtime'] . "</span>
                                        </div>
                                    </h3>
                                    <p>"
						. $todos[$i]['remark'] .
						"</p>
					</div>
				</div>";
				}
				echo $html;
				exit;
			case 'del_todo':
				$this->__check_istrue($todo->del($data));
			case 'maybe_todo':
				$this->__check_istrue($todo->maybe($data));
			case 'get_edit_todo_dialog':
				$v = $todo->get($data);
				if ($v) {
					echo "<form class=\"full validate\">
                            <div class=\"row\">
                                <label for=\"todo_name\">
                                    <strong>
                                        事件名称
                                    </strong>
                                </label>
                                <div class=\"_80 input\">
                                    <input class=\"required\" type=text name=edit_todo_name value=" . $v['name'] . " />
                                </div>
                            </div>
                            <div class=\"row\">
                                <label for=\"todo_remark\">
                                    <strong>
                                        事件备注
                                    </strong>
                                </label>
                                <div class=\"_80 input\">
                                    <textarea class=\"editor\" rows=3 name=\"edit_todo_remark\" id=\"f1_textarea\">" . $v['remark'] . "</textarea>
                                </div>
                            </div>
                            <div class=\"row\">
                                <label for=\"todo_endtime\">
                                    <strong>
                                        开始时间
                                    </strong>
                                </label>
                                <div class=\"_80 input\">
                                    <input type=\"datetime\" name=\"edit_todo_startime\" id=\"f3_datepicker\" value=\"" . $v['startime'] . "\">
                                </div>
                            </div>
                            <div class=\"row\">
                                <label for=\"todo_endtime\">
                                    <strong>
                                        结束时间
                                    </strong>
                                </label>
                                <div class=\"_80 input\">
                                    <input type=\"datetime\" name=\"edit_todo_endtime\" value=\"" . $v['endtime'] . "\">
                                </div>
                            </div>
                            <div class=\"row\">
                                <label for=\"todo_tags\">
                                    <strong>
                                         所属事件
                                    </strong>
                                </label>
                                <div class=\"_80 input\">
                                    <select name=\"edit_todo_tags\" class=\"search required\" data-placeholder=\"请选择所属事项\">
                                        <option value=\"\">
                                        </option>
                                        " . $this->__get_tags($v['tags']) . "
                                    </select>
                                </div>
                            </div>
                            <div class=\"row\">
                                <label for=\"todo_level\" class=\"tooltip\" data-gravity=\"nw\" original-title=\"执行事件的优先级\">
                                    <strong>
                                        优先级&nbsp;&nbsp;&nbsp;&nbsp;
                                    </strong>
                                </label>
                                <div class=\"_80 input\">
                                    <select name=\"edit_todo_level\" data-placeholder=\"请选择优先级\">
                                        <option value=\"0\" " . $this->__check_equally($v['level'], 0) . ">
                                            低
                                        </option>
                                        <option value=\"1\" " . $this->__check_equally($v['level'], 1) . ">
                                            中
                                        </option>
                                        <option value=\"2\" " . $this->__check_equally($v['level'], 2) . ">
                                            高
                                        </option>
                                    </select>
                                </div>
                            </div>
                            <div class=\"row\">
                                <label for=\"edit_todo_repeats\" class=\"tooltip\" data-gravity=\"nw\" original-title=\"是否按照预订时间重复提醒\">
                                    <strong>
                                        重复&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                    </strong>
                                </label>
                                <div class=\"_80 input\">
                                    <select name=\"edit_todo_repeats\" data-placeholder=\"是否按照预订时间重复提醒\">
                                        <option value=\"0\" " . $this->__check_equally($v['repeats'], 0) . ">
                                            不重复
                                        </option>
                                        <option value=\"1\" " . $this->__check_equally($v['repeats'], 1) . ">
                                            每天
                                        </option>
                                        <option value=\"2\" " . $this->__check_equally($v['repeats'], 2) . ">
                                            每工作日
                                        </option>
                                        <option value=\"3\" " . $this->__check_equally($v['repeats'], 3) . ">
                                            每周
                                        </option>
                                        <option value=\"4\" " . $this->__check_equally($v['repeats'], 4) . ">
                                            每月
                                        </option>
                                        <option value=\"5\" " . $this->__check_equally($v['repeats'], 5) . ">
                                            每年
                                        </option>
                                    </select>
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
				} else {
					$this->__show_result('Get_edit_todo_dialog find', 'error');
				}
				break;
			case 'add_tag':
				$this->__check_istrue($tags->add($data));
			case 'del_tag':
				$this->__check_istrue($tags->del($data));
			case 'get_tag_lists':
				$tagss = $tags->getAll();
				for ($i = 0; $i < count($tagss); $i++) {
					$tags->maybe($tagss[$i]['tagid']);
				}
				$this->tagss = $tagss;
				if (count($tagss) == 0) {
					echo "<span>请先添加分类</span>";
				} else {
					foreach ($tagss as $tag) {
						if ($data['get'] == 'todo') {
							$tag['num'] = $tag['total'];
						} else if ($data['get'] == 'maybe') {
							$tag['num'] = $tag['maybe'];
						}
						echo "<div class=\"tag\">
                        <span class=\"" . $tag['icon'] . "\">
                            " . $tag['name'] . "(<div style=\"display:inline\" class=\"tag_" . $tag['tagid'] . "\">" . $tag['num'] . "</div>项)
                        </span>
                        <div class=\"right inline m_b\">
                            <a href=\"javascript:void(0)\" onclick=\"Todo.del_tag(" . $tag['tagid'] . ")\" class=\"button small grey tooltip\" data-gravity=\"w\" original-title=\"删除【" . $tag['name'] . "】\">
                                <i class=\"icon-remove\"></i>
                            </a>
                        </div>
                        </div>";
					}
				}
				exit;
		}
	}

	function maybe()
	{
		//Todo
		$todo = FeiClass('model_todo');
		//Tags
		$tags   = FeiClass('model_todo_tags');
		$action = $this->FeiArgs('action', NULL, 'POST');
		$data   = $this->FeiArgs(NULL, NULL, 'POST');
		switch ($action) {
			case 'ajax_load_todo_content':
				$maybes = $todo->maybes($data);
				$count  = count($maybes);
				for ($i = 0; $i < $count; $i++) {
					$html .= "<div class=msg>
                                <div class=left>
                                    <button onclick=\"Completed_Todo(" . $maybes[$i]['doid'] . ")\" id=completed_button_" . $maybes[$i]['doid'] . " class=\"button grey block\">
                                         <span class=\"icon icon-check-empty\"></span>
                                                确定完成
                                         </button>
                                         <ul class=\"buttons\">
                                              <li>
                                              <a onclick=Todo.maybe(" . $maybes[$i]['doid'] . ") class=\"open-message-dialog\" title=\"移动到[将来或许]\">
                                              <img src=\"statics/img/icons/packs/iconsweets2/16x16/bended-arrow-right.png\" alt= />
                                              </a>
                                         </li>
                                        <li>
                                            <a onclick=Todo.edit(" . $maybes[$i]['doid'] . ") href=\"javascript:void(0);\" title=\"修改\">
                                                <img src=\"statics/img/icons/packs/iconsweets2/16x16/create---write.png\" />
                                            </a>
                                        </li>
                                        <li>
                                            <a href=\"javascript:void(0);\" onclick=\"Todo.del(" . $maybes[$i]['doid'] . ")\" title=\"删除\">
                                                <img src=\"statics/img/icons/packs/iconsweets2/16x16/trashcan-2.png\" />
                                            </a>
                                        </li>
                                    </ul>
                                </div>
                                <div class=\"content\">
                                    <h3>"
						. $maybes[$i]['name'] .
						"<div style=\"display: inline;float:right;\">
							" . $this->__check_repeats($maybes[$i]['doid']) . "<span class=\"icon-time\"> " . $maybes[$i]['endtime'] . "</span>
                                        </div>
                                    </h3>
                                    <p>"
						. $maybes[$i]['remark'] .
						"</p>
					</div>
				</div>";
				};
				echo $html;
				exit;
			default:
				$this->tagss = $tags->getAll();
				break;
		}
	}

	/**
	 * 固定事件
	 **/
	function fixed()
	{
		$todo   = FeiClass('model_todo');
		$action = $this->FeiArgs('action', NULL, 'POST');
		$data   = $this->FeiArgs(NULL, NULL, 'POST');
		switch ($action) {
			#主数据
			case 'ajax_load_fixed_content':
				$todos = $todo->fixeds();
				foreach ($todos as $v) {
					echo "<div class=msg>
                        <div class=left>
                            <button onclick=\"Cancle_Trash_Todo(" . $v['doid'] . ")\" id=cancle_trash_button_" . $v['doid'] . " class=\"button grey block\">
                                 <span class=\"icon icon-check-empty\"></span>
                                        固定提醒 
                                 </button>
                        </div>
                        <div class=\"content\">
                            <h3>"
						. $v['name'] .
						"<div style=\"display: inline;float:right;\">
							<span class=\"icon-time\">" . $v['endtime'] . "</span>
                                </div>
                            </h3>
                            <p>"
						. $v['remark'] .
						"</p>
					</div>
				</div>";
				};
				exit;
			#添加
			case 'ajax_add_fixed':
				$this->__check_istrue($todo->add_fixed($data));
			#输出页面
			default:
				#
		}
	}


	/**
	 * 收集回顾页
	 * @param
	 * @return
	 */
	function collect()
	{
//        $mail = FeiClass('FeiEmail');
//        $mailsubject = "SpeedPHP邮件扩展";//邮件主题
//        $mailbody = "<h1> SpeedPHP邮件扩展 </h1>";//邮件内容
//        $mailtype = "HTML";//邮件格式（HTML/TXT）,TXT为文本邮件
//        $mail->sendmail('398397685@qq.com', $mailsubject, $mailbody, $mailtype);

//        $mail = FeiClass(phpmailer);
//        $address ="398397685@qq.com";
//        $mail->IsSMTP(); // 使用SMTP方式发送
//        $mail->Host = "smtp.126.com"; // 您的企业邮局域名
//        $mail->SMTPAuth = true; // 启用SMTP验证功能
//        $mail->Username = "syrdxb@126.com"; // 邮局用户名(请填写完整的email地址)
//        $mail->Password = "13328552116."; // 邮局密码
//        $mail->Port=25;
//        $mail->From = "syrdxb@126.com"; //邮件发送者email地址
//        $mail->FromName = "liuyoubin";
//        $mail->AddAddress("$address", "a");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
//        //$mail->AddReplyTo("", "");
//
//        //$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
//        //$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
//
//        $mail->Subject = "PHPMailer测试邮件"; //邮件标题
//        $mail->Body = "Hello,这是测试邮件"; //邮件内容
//        $mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
//
//        if(!$mail->Send())
//        {
//        echo "邮件发送失败. <p>";
//        echo "错误原因: " . $mail->ErrorInfo;
//        exit;
//        }
//
//        echo "邮件发送成功";
		$todo = FeiClass(model_todo);
		if (isset($_POST['action']) && $_POST['action'] == 'alltodo') {
			$conditions = "userid = " . $_SESSION['Fei_Userid'] . " AND (status=0 OR status=1 OR status=6)";
			if ($todo->findAll($conditions)) {
				$todolist = $todo->findAll($conditions, NULL, 'name,endtime,createtime,startime');
				$count    = count($todolist);
				for ($i = 0; $i <= $count; $i++) {
					$todolists[$i]['title'] = $todolist[$i]['name'];
					$todolists[$i]['start'] = date('D M d Y H:i:s \\G\\M\\TO (T)', strtotime($todolist[$i]['createtime']));
//                    $todolists[$i]['end'] = date('D M d Y H:i:s \\G\\M\\TO (T)',strtotime($todolist[$i]['endtime'])+3600);
					$todolists[$i]['allDay'] = FALSE;
				}
				//Get Festival
				// $festival = FeiClass(model_festival);
				// $festivals = $festival->findAll();
				// $f_count = count($festivals);
				// for($j = 0;$j <= $f_count;$j++){
				//     $festival_lists[$j]['title'] = '【'.$festivals[$j]['name'].'】';
				//     $festival_lists[$j]['start'] = date('D M d Y H:i:s \\G\\M\\TO (T)',strtotime($festivals[$j]['date']));
				// }
				//dump(array_merge($todolists,$festival_lists));exit;
				//echo json_encode(array_merge($todolists,$festival_lists));exit;
				echo json_encode($todolists);
				exit;
			}
		} else {

		}
	}

	/**
	 * @category 回顾页
	 * @param
	 * @return
	 */
	function review()
	{

	}

	/**
	 * 检测是否重复
	 * @param $doid
	 * @return span
	 */
	function __check_repeats($doid)
	{
		$todo       = FeiClass(model_todo);
		$conditions = array(
			'doid'   => $doid,
			'userid' => $_SESSION['Fei_Userid']
		);
		$todo_info  = $todo->find($conditions);
		if ($todo_info) {
			switch ($todo_info['repeats']) {
				case 0:
					return '';
					break;
				case 1:
					return "<span class=\"icon-refresh tooltip\" style=\"margin:0px 5px\" title=\"每天重复事件\"></span>";
					break;
				case 2:
					return "<span class=\"icon-refresh tooltip\" style=\"margin:0px 5px\" title=\"每个工作日重复事件\"></span>";
					break;
				case 3:
					return "<span class=\"icon-refresh tooltip\" style=\"margin:0px 5px\" title=\"每周重复事件\"></span>";
					break;
				case 4:
					return "<span class=\"icon-refresh tooltip\" style=\"margin:0px 5px\" title=\"每月重复事件\"></span>";
					break;
				case 5:
					return "<span class=\"icon-refresh tooltip\" style=\"margin:0px 5px\" title=\"每年重复事件\"></span>";
					break;
			}
		}
	}

	/**
	 * 将TAG传入模板
	 * @param
	 * @return icon 图标名称 | error
	 */
	function __get_tag($tagid)
	{
		$tag        = FeiClass(model_todo_tags);
		$conditions = array(
			'tagid'  => $tagid,
			'userid' => $_SESSION['Fei_Userid']
		);
		$tag_info   = $tag->find($conditions);
		if ($tag_info) {
			return $tag_info['icon'];
		} else {
			return 'error';
		}
	}

	function __get_tags($todo_tag)
	{
		$tags       = FeiClass(model_todo_tags);
		$conditions = array(
			'userid' => $_SESSION['Fei_Userid']
		);
		$this->tags = $tags->findAll($conditions);
		foreach ($this->tags as $tag) {
			$str .= "<option value=\"" . $tag['tagid'] . "\" " . $this->__check_equally($todo_tag, $tag['tagid']) . ">" . $tag['name'] . "</option>";
		}
		return $str;
	}

	function __check_in_array($n, $c)
	{
		$tags = explode("@", $n);
		if (in_array($c, $tags)) {
			return "selected";
		}
	}

	function __check_equally($n, $c)
	{
		if ($n == $c) {
			return 'selected';
		}
	}

	function __istoday($data)
	{
		$day    = strtotime($data);
		$day    = date('d', $day);
		$nowday = date('d', time());
		if ($day == $nowday) {
			return TRUE;
		} else {
			return FALSE;
		}
	}

	function __format_date($endtime)
	{
		$time    = $endtime;
		$endtime = strtotime($endtime);
		$nowtime = time();
		//NOW TIME
		$toyear   = date('Y', $nowtime); //年
		$tomouth  = intval(date('m', $nowtime)); //月
		$today    = intval(date('d', $nowtime)); //日
		$tohour   = intval(date('H', $nowtime)); //时
		$tominute = intval(date('i', $nowtime)); //分
		//END TIME
		$endyear   = date('Y', $endtime); //年
		$endmouth  = intval(date('m', $endtime)); //月
		$endday    = intval(date('d', $endtime)); //日
		$endhour   = intval(date('H', $endtime)); //时
		$endminute = intval(date('i', $endtime)); //分

		if ($toyear == $endyear && $tomouth == $endmouth) { //年月相同
			if ($today > $endday && $endhour < 12) { //今天之前 AM
				if ($endday == date('d', strtotime('-1 day'))) { //昨天
					return '昨天上午' . $endhour . '点';
				} elseif ($endday == date('d', strtotime('-2 day'))) { //前天
					return '前天上午' . $endhour . '点';
				} elseif ($endday == date('d', strtotime('-3 day'))) { //大前天
					return '大前天上午' . $endhour . '点';
				}
			} elseif ($today > $endday && $endhour > 12) { //今天之前 PM
				if ($endday == date('d', strtotime('-1 day'))) { //昨天
					return '昨天下午' . ($endhour - 12) . '点';
				} elseif ($endday == date('d', strtotime('-2 day'))) { //前天
					return '前天下午' . ($endhour - 12) . '点';
				} elseif ($endday == date('d', strtotime('-3 day'))) { //大前天
					return '大前天下午' . ($endhour - 12) . '点';
				}
			} elseif ($today < $endday && $endhour < 12) { //今天之后 AM
				if ($endday == date('d', strtotime('+1 day'))) { //明天
					return '明天上午' . $endhour . '点';
				} elseif ($endday == date('d', strtotime('+2 day'))) { //后天
					return '后天上午' . $endhour . '点';
				} elseif ($endday == date('d', strtotime('+3 day'))) { //大后天
					return '大后天上午' . $endhour . '点';
				}
			} elseif ($today < $endday && $endhour > 12) { //今天之后 PM
				if ($endday == date('d', strtotime('+1 day'))) { //明天
					return '明天下午' . ($endhour - 12) . '点';
				} elseif ($endday == date('d', strtotime('+2 day'))) { //后天
					return '后天下午' . ($endhour - 12) . '点';
				} elseif ($endday == date('d', strtotime('+3 day'))) { //大后天
					return '大后天下午' . ($endhour - 12) . '点';
				}
			} elseif ($today == $endday) { //今天
				if ($endhour < 12) { //AM
					return '上午' . $endhour . '点';
				} elseif ($endhour > 12) { //PM
					return '下午' . ($endhour - 12) . '点';
				}
			} else { //不是今天/前天/大前天/后天/大后天
				return $time;
			}
		} else if ($toyear == $endyear) { //年相同
			if ($endyear < $toyear) { //以前年份
				if ($endyear == date('y', strtotime('-1 year'))) {
					return '去年' . $endmouth . '月' . $endday;
				} else if ($endyear == date('y', strtotime('-2 year'))) {
					return '前年' . $endmouth . '月' . $endday;
				} else if ($endyear == date('y', strtotime('-3 year'))) {
					return '大前年' . $endmouth . '月' . $endday;
				}
			} else if ($endyear > $toyear) { //大于现在年份
				if ($endyear == date('y', strtotime('+1 year'))) {
					return '明年' . $endmouth . '月' . $endday;
				} else if ($endyear == date('y', strtotime('+2 year'))) {
					return '后年' . $endmouth . '月' . $endday;
				} else if ($endyear == date('y', strtotime('+3 year'))) {
					return '大后年' . $endmouth . '月' . $endday;
				}
			}

		} else { //年月不同
			return $time;
		}
	}
}