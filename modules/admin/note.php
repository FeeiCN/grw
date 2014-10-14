<?php

/**
 * 记事本（待废弃）
 * Class note
 */
class note extends Grw
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('note');
	}

	function index()
	{
		$note   = FeiClass('model_note');
		$action = $this->FeiArgs('action', NULL, 'POST');
		$data   = $this->FeiArgs(NULL, NULL, 'POST');
		switch ($action) {
			case 'view_note':
				$this->__check_istrue($n = $note->view($data), $n);
				break;
			case 'add_note':
				$this->__check_istrue($note->add($data));
				break;
			case 'edit_note':
				$this->__check_istrue($note->edit($data));
				break;
			case 'del_note':
				$this->__check_istrue($note->del($data));
				break;
			case 'get_note':
				$notes = $note->findAll();
				for ($i = 0; $i < count($notes); $i++) {
					$notes[$i]['content'] = $this->__cut_str($notes[$i]['content'], 140);
				}
				#Construct html
				for ($i = 0; $i < count($notes); $i++) {
					$d = date('d', strtotime($notes[$i]['createtime']));
					$m = date('m', strtotime($notes[$i]['createtime']));
					$y = date('Y', strtotime($notes[$i]['createtime']));
					$note_html .= "
						<div class=\"entry\"> 
                            <div class=\"meta\"> 
                                <div class=\"date\"> 
                                    <div class=\"day\">" . $d . "号</div>
                                    <div class=\"month\">" . $m . "月</div>
                                    <div class=\"year\">" . $y . "年</div>
                                </div>
                            </div> 
                            <div class=\"content\"> 
                                <h3 onclick=\"Note.view(" . $notes[$i]['id'] . ")\">" . $notes[$i]['title'] . " </h3>
                                <h4>" . $notes[$i]['keywords'] . "</h4>
                                <button class=\"grey\" onclick=\"Note.edit(" . $notes[$i]['id'] . ")\">修改</button>
                                <button class=\"red\" onclick=\"Note.del(" . $notes[$i]['id'] . ")\">删除</button>
                            </div> 
                        </div>
					";
				}
				echo $note_html;
				exit;
			default:
				$notes = $note->findAll();
				for ($i = 0; $i < count($notes); $i++) {
					$notes[$i]['content'] = $this->__cut_str($notes[$i]['content'], 140);
				}
				$this->notes = $notes;
				break;
		}
	}

	/*
	Utf-8、gb2312都支持的汉字截取函数
	cut_str(字符串, 截取长度, 开始长度, 编码);
	编码默认为 utf-8
	开始长度默认为 0
	*/
	function __cut_str($string, $sublen, $start = 0, $code = 'UTF-8')
	{
		if ($code == 'UTF-8') {
			$pa = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|\xe0[\xa0-\xbf][\x80-\xbf]|[\xe1-\xef][\x80-\xbf][\x80-\xbf]|\xf0[\x90-\xbf][\x80-\xbf][\x80-\xbf]|[\xf1-\xf7][\x80-\xbf][\x80-\xbf][\x80-\xbf]/";
			preg_match_all($pa, $string, $t_string);
			if (count($t_string[0]) - $start > $sublen) return join('', array_slice($t_string[0], $start, $sublen)) . "...";
			return join('', array_slice($t_string[0], $start, $sublen));
		} else {
			$start  = $start * 2;
			$sublen = $sublen * 2;
			$strlen = strlen($string);
			$tmpstr = '';
			for ($i = 0; $i < $strlen; $i++) {
				if ($i >= $start && $i < ($start + $sublen)) {
					if (ord(substr($string, $i, 1)) > 129) {
						$tmpstr .= substr($string, $i, 2);
					} else {
						$tmpstr .= substr($string, $i, 1);
					}
				}
				if (ord(substr($string, $i, 1)) > 129) $i++;
			}
			if (strlen($tmpstr) < $strlen) $tmpstr .= "...";
			return $tmpstr;
		}
	}
}