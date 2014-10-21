<?php

/**
 * 个人网主类
 * Class Grw
 */
class Grw extends FeiController
{
	function __construct()
	{
		parent::__construct();
		$this->__initial('Grw');
		$this->PIC     = "http://www.grw.name/upload/";
		$this->STATICS = "http://www.grw.name/statics/";
		//ini_set('memory_limit', '-1');
		$this->userid = $_SESSION['Fei_Userid'];
		$this->logs   = FeiClass('FeiLog');
		if ($this->__is_ie()) {
			$this->isie = TRUE;
		} else {
			$this->isie = FALSE;
		}
	}

	/**
	 * Index
	 */
	function index()
	{

	}

	/**
	 * 登陆
	 */
	function login()
	{
		if (FeiClass(FeiAcl)->get() == 'Fei_Admin') { //already login
			$this->jump(FeiUrl('Grw'));
		} else if (isset($_POST['username'])) { //validate basic login
			$user       = FeiClass(model_user);
			$conditions = array(
				'username' => $this->FeiArgs('username'),
				'password' => $this->FeiArgs('password')
			);
			if ($user->find($conditions)) { //Login Success
				$user_info = $user->find($conditions);
				FeiClass(FeiAcl)->set('Fei_Admin');
				$_SESSION['Fei_Userid']   = $user_info['id'];
				$_SESSION['Fei_Realname'] = $user_info['realname'];
				setcookie('Fei_Userid', $user_info['id']);
				$this->jump(FeiUrl('Grw'));
			} else {
				$this->error('用户名或密码错误！', FeiUrl('Grw'));
			}
		} /*else if(isset($_POST['action']) && $_POST['action'] == 'register'){#创建用户
                if(!empty($_SESSION['openid'])){
                    $row = array(
                        'openid'=>$_SESSION['openid'],
                        'avatar'=>$this->FeiArgs('avatar'),
                        'realname'=>$this->FeiArgs('realname'),
                        'email'=>$this->FeiArgs('email'),
                        'birthday'=>$this->FeiArgs('birthday'),
                        'sexy'=>$this->FeiArgs('sexy'),
                        'question'=>$this->FeiArgs('question'),
                        'answer'=>$this->FeiArgs('answer')
                    );
                    $user = FeiClass('model_user');
                    $this->__check_istrue($user->create($row));
                }else{
                    $this->__show_result('已过期！','error');
                }
            }*/ else if (isset($_GET['state']) && $_GET['state'] == $_SESSION['qq_state']) { #login for qq
			$openid             = FeiClass('FeiQQlogin')->openid();
			$_SESSION['openid'] = $openid;
			$user               = FeiClass('model_user');
			$ucon               = array(
				'openid' => $openid
			);
			if ($user_info = $user->find($ucon)) { #if find openid then login in
				#@TODO:check avatar is_file
				if (is_file('statics/images/avatar/' . $user_info['avatar'] . '.jpg')) {

				} else {

				}
				FeiClass(FeiAcl)->set('Fei_Admin');
				$_SESSION['Fei_Userid']   = $user_info['id'];
				$_SESSION['Fei_Realname'] = $user_info['realname'];
				setcookie('Fei_Userid', $user_info['id']);
				$this->jump(FeiUrl('Grw'));
			} else { #if non't find openid them create new user
				$userinfo = FeiClass('FeiQQlogin')->get_user_info($openid);
				$userinfo = json_decode($userinfo);
				/*
				#注册页面
				#昵称
				$this->nickname = $userinfo->nickname;
				#40*40
				$this->avatar = $userinfo->figureurl_qq_1;
				#100*100
				$this->avatar2 = $userinfo->figureurl_qq_2;
				#@return 男 女
				$this->gender = $userinfo->gender;
				$this->display("Grw/check.html");
				*/
				#创建用户
				$row  = array(
					'openid'   => $openid,
					'realname' => $userinfo->nickname,
					'avatar'   => $userinfo->figureurl_qq_2,
					'gender'   => $userinfo->gender
				);
				$user = FeiClass('model_user');
				if ($uid = $user->create($row)) {
					FeiClass('FeiAcl')->set('Fei_Admin');
					$_SESSION['Fei_Userid']   = $uid;
					$_SESSION['Fei_Realname'] = $userinfo->nickname;
					$this->jump(FeiUrl('Grw'));
				} else {
					echo 'register error';
					exit;
				}

			}
		} else { //show login
//                $this->__initial();
//                $this->jump('http://www.grw.name/index.php?c=Grw&a=login');

			#qq login 开发人员离线开发请注释，并在数据库fei_user表中添加一个条新数据，使username, password字段有值
			FeiClass('FeiQQlogin')->login();
		}
	}

	/**
	 * 退出登陆
	 * @TODO 记录退出登陆时间
	 */
	function logout()
	{
		$_SESSION = array();
		if (isset($_COOKIE[session_name()])) {
			setcookie(session_name(), '', time() - 42000, '/');
		}
		FeiClass('FeiAcl')->set("");
		session_destroy();
		$this->success("已退出！", 'http://www.grw.name');
	}

	/**
	 * 检测是否是IE浏览器
	 * @return Boolean
	 */
	function __is_ie()
	{
		$useragent = strtolower($_SERVER['HTTP_USER_AGENT']);
		if ((strpos($useragent, 'opera') !== FALSE) || (strpos($useragent, 'konqueror') !== FALSE)) return FALSE;
		if (strpos($useragent, 'msie ') !== FALSE) return TRUE;
		return FALSE;
	}

	/**
	 * 获取用户提示
	 */
	function alert()
	{
		#用户信息提示
		$user = FeiClass('model_user');
		$con  = array('id' => $_SESSION['Fei_Userid']);
		if ($userinfo = $user->find($con)) {
			#联系信息
			$url = FeiUrl('profile');
			if ($userinfo['email'] == NULL) {
				$this->__alert('warning', "必须填写邮箱才能接收到各类通知哦！[<a href=" . $url . ">立即填写</a>]");
			}
			if ($userinfo['phone'] || $userinfo['qq'] || $userinfo['address']) {
				$this->__alert('information', "完善个人信息将获得金币哦！[<a href=" . $url . ">点击完善</a>]");
			}
			exit;

		} else {
			#查询出错
		}
	}

	/**
	 * 输出提示
	 * @param $method
	 * @param $msg
	 */
	function __alert($method, $msg)
	{
		switch ($method) {
			case 'error':
				echo "
                    <div class=\"alert error\">
                        <span class=\"icon\">
                        </span><span class=\"close\">x</span>
                        <strong>
                            错误
                        </strong>
                        " . $msg . "
                    </div>";
				break;
			case 'success':
				echo "
                    <div class=\"alert success\">
                        <span class=\"icon\">
                        </span><span class=\"close\">x</span>
                        <strong>
                            成功
                        </strong>
                        " . $msg . "
                    </div>";
				break;
			case 'warning':
				echo "
                    <div class=\"alert warning\">
                        <span class=\"icon\">
                        </span><span class=\"close\">x</span>
                        <strong>
                            警告
                        </strong>
                        " . $msg . "
                    </div>";
				break;
			case 'information':
				echo "
                    <div class=\"alert information\">
                        <span class=\"icon\">
                        </span><span class=\"close\">x</span>
                        <strong>
                            信息
                        </strong>
                        " . $msg . "
                    </div>";
				break;
			case 'note':
				echo "
                    <div class=\"alert note\">
                        <span class=\"icon\">
                        </span><span class=\"close\">x</span>
                        <strong>
                            提示:
                        </strong>
                        " . $msg . "
                    </div>";
				break;
		}
	}

	/**
	 * 初始化
	 * @param $c
	 */
	function  __initial($c)
	{
		$this->setLang('zh-cn');
		$this->gg = FeiClass('TemplateParse');
		$category = FeiClass(model_category);
		if ($_SESSION['Fei_Userid'] == 1) {
			$TopCategory = $category->findAll(NULL, 'listorder ASC');
		} else {
			$conditions  = array(
				'ismenu' => 1
			);
			$TopCategory = $category->findAll($conditions, 'listorder ASC');
		}


		foreach ($TopCategory as $k => $v) {
			if (isset($v['catid']) and !empty($v['catid'])) {
				if ($this->__haschild($v['catid'])) { //有子栏目
					$TopCategory[$k]['haschild'] = 1;
				} else { //没有子栏目
					$TopCategory[$k]['haschild'] = 0;
				}
				$TopCategory[$k]['num'] = $this->__get_cate_num($v['catid']);
				if ($v['parentid'] == 0) {
					$TopCategory[$k]['url'] = 'http://www.grw.name/admin.php?c=' . $v['letter'];
				} else {
					$TopCategory[$k]['url'] = 'http://www.grw.name/admin.php?c=' . $this->__get_pname_bypd($v['parentid']) . '&a=' . $v['letter'];
				}
			}
		}
		$this->TopCategory = $TopCategory;
		//dump($this->TopCategory);
		//读取用户基本信息
		if (isset($_SESSION['Fei_Userid']) && !empty($_SESSION['Fei_Userid'])) {
			$user       = FeiClass(model_user);
			$this->User = $user->findBy('id', $_SESSION['Fei_Userid']);
		}
	}

	/**
	 * 获取总的待办事项数
	 * @param $catid
	 * @return bool|int
	 */
	function __get_cate_num($catid)
	{
		$todo = FeiClass('model_todo');
		if ($catid == 48) { //今日待办
			return $this->__get_cate_num2('48');
		} else if ($catid == 64) {
			return $this->__get_cate_num2('64');
		} else if ($catid == 47) {
			return $this->__get_cate_num2('48') + $this->__get_cate_num2('64');
		}
	}

	/**
	 * 获取栏目待办事项数
	 * @param $catid
	 * @return bool|int
	 */
	function __get_cate_num2($catid)
	{
		$todo = FeiClass('model_todo');
		switch ($catid) {
			case '48': //今日代办
				$con   = array(
					'status' => 0,
					'userid' => $_SESSION['Fei_Userid']
				);
				$todos = $todo->findAll($con, 'endtime ASC');
				return count($todos);
				break;
			case '64': //也许可能
				$con   = array(
					'status' => 3,
					'userid' => $_SESSION['Fei_Userid']
				);
				$todos = $todo->findAll($con, 'endtime ASC');
				return count($todos);
				break;
			default:
				return FALSE;
				break;
		}
	}

	/**
	 * 检测是否有子栏目
	 * @param $catid
	 * @return bool
	 */
	function __haschild($catid)
	{
		$category   = FeiClass('model_category');
		$conditions = array('parentid' => $catid);
		$count      = $category->findCount($conditions);
		if ($count != 0) return TRUE;
	}

	/**
	 * 根据父ID获取父栏目名称
	 * @param $parentid
	 * @return mixed
	 */
	function __get_pname_bypd($parentid)
	{
		$category = FeiClass('model_category');
		$name     = $category->findBy('catid', $parentid);
		return $name['letter'];
	}

	/**
	 * 检测对象是否真并返回JSON
	 * @param $obj
	 * @param $b
	 */
	function __check_istrue($obj, $b)
	{
		if ($obj) {
			$result = array(
				'status' => 'success',
				'back'   => $b
			);
			echo json_encode($result);
			exit;
		} else {
			$result = array(
				'status' => 'error'
			);
			echo json_encode($result);
			exit;
		}
	}

	/**
	 * 返回错误信息
	 * @param $event
	 * @param $status
	 */
	function __show_result($event, $status)
	{
		if ($status == 'success') {
			$result = array(
				'status' => 'success'
			);
			echo json_encode($result);
			exit;
		} else if ($status == 'error') {
			$result = array(
				'status' => 'error'
			);
			echo json_encode($result);
			exit;
		}
	}

	/**
	 * 天气接口
	 * @TODO 移到api.php里
	 */
	function weather()
	{
		//weather
		$AppKey   = "bddcda9cbf587887d09a5cdc02d5e3a3";
		$cityData = json_decode($this->__Get_city());
		$city     = $cityData->data->city;
		$len      = $this->utf8_strlen($city);
		$city     = $this->utf8Substr($city, 0, $len - 1);
		$url      = 'http://v.juhe.cn/weather/index?cityname=' . $city . '&key=' . $AppKey;
		$data     = json_decode($this->__Get_content($url), TRUE);
		if ($data['resultcode'] == 200) {
			//农历
			$lunar    = FeiClass('FeiLunar');
			$today    = date('Y-m-d');
			$nl_month = $lunar->LMonName(intval(date("m", $lunar->S2L($today))));
			$nl_day   = $lunar->LDayName(intval(date("d", $lunar->S2L($today))));
			$nl       = $nl_month . "月" . $nl_day; //nong li
			$month    = intval(date("m"));
			$day      = intval(date("d"));
			$td       = $month . "月" . $day . "日"; //today
			$mt       = $data['result']['future']['day_' . date("Ymd", strtotime("+1 day"))]; //mingtian
			$ht       = $data['result']['future']['day_' . date("Ymd", strtotime("+2 day"))]; //mingtian
			switch ($data['result']['today']['weather']) {
				case '阴转小雨':
					$w_ico = "8";
					break;
				case '晴':
					$w_ico = "1";
					break;
				case '阴':
					$w_ico = "3";
					break;
				case '多云':
					$w_ico = "5";
					break;
				case '小雨':
					$w_ico = "11";
					break;
				case '小雨转多云':
					$w_ico = "8";
					break;
				case '中雨转小雨':
					$w_ico = '9';
					break;
				case '阵雨':
					$w_ico = "12";
					break;
				case '雨夹雪':
					$w_ico = '14';
					break;
				case '小雪':
					$w_ico = '17';
					break;
				case '中雪':
					$w_ico = '18';
					break;
				case '大雪':
					$w_ico = '19';
					break;
				case '暴风雪':
					$w_ico = '20';
					break;
				case '阵雨转多云':
					$w_ico = '9';
					break;
				case '暴雨':
					$w_ico = '13';
					break;
				default:
					$this->logs->NOTICE($data['result']['today']['weather']);
					$w_ico = "7";
			}
			$weather = array(
				'status'  => 'success',
				'td'      => $td,
				'nl'      => $nl,
				'weather' => $data['result']['today']['weather'],
				'wd'      => $data['result']['today']['temperature'],
				'ico'     => $w_ico,
				'week'    => $data['result']['today']['week'],
				'jy'      => $data['result']['today']['dressing_advice'],
				'mtd'     => $mt['temperature'],
				'mtq'     => $mt['weather'],
				'htd'     => $ht['temperature'],
				'htq'     => $ht['weather']
			);
		} else {
			$weather = array(
				'status' => 'error'
			);
		}
		echo json_encode($weather);
		exit;
	}

	/**
	 * 获取当前用户IP
	 * @return string
	 */
	function __Get_ip()
	{
		if (getenv("HTTP_CLIENT_IP"))
			$ip = getenv("HTTP_CLIENT_IP");
		else if (getenv("HTTP_X_FORWARDED_FOR"))
			$ip = getenv("HTTP_X_FORWARDED_FOR");
		else if (getenv("REMOTE_ADDR"))
			$ip = getenv("REMOTE_ADDR");
		else $ip = "Unknow";
		return $ip;
	}

	/**
	 * 获取当前用户所在城市
	 * @return mixed
	 */
	function __Get_city()
	{
		$ip   = $this->__Get_ip();
		$url  = 'http://ip.taobao.com/service/getIpInfo.php?ip=' . $ip;
		$data = $this->__Get_content($url);
		return $data;
	}

	/**
	 * 请求远程数据
	 * @param $url
	 * @return mixed
	 */
	function __Get_content($url)
	{
		$ch      = curl_init();
		$timeout = 5;
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$file_contents = curl_exec($ch);
		curl_close($ch);
		return $file_contents;
	}

	/**
	 * 中文字符截断
	 * @param $str
	 * @param $from
	 * @param $len
	 * @return mixed
	 */
	function utf8Substr($str, $from, $len)
	{
		return preg_replace('#^(?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $from . '}' .
			'((?:[\x00-\x7F]|[\xC0-\xFF][\x80-\xBF]+){0,' . $len . '}).*#s',
			'$1', $str);
	}

	/**
	 * 字符长度计算
	 * @param null $string
	 * @return int
	 */
	function utf8_strlen($string = NULL)
	{
		// 将字符串分解为单元
		preg_match_all("/./us", $string, $match);
		// 返回单元个数
		return count($match[0]);
	}


}