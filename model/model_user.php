<?php

class model_user extends FeiModel
{
	var $pk = "id";
	var $table = "user";

	/**
	 * 这里我们建立一个成员函数来进行用户登录验证
	 * @param uname    用户名
	 * @param upass    密码，请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
	 */
	public function userlogin($uname, $upass)
	{
		$conditions = array(
			'uname' => $uname,
			'upass' => $upass, // 请注意，本例中使用了加密输入框，所以这里的$upass是经过MD5加密的字符串。
		);
		// dump($conditions);
		// 检查用户名/密码，由于$conditions是数组，所以SP会自动过滤SQL攻击字符以保证数据库安全。
		if ($result = $this->find($conditions)) {
			// 成功通过验证，下面开始对用户的权限进行会话设置，最后返回用户ID
			// 用户的角色存储在用户表的acl字段中
			spClass('FeiAcl')->set($result['acl']); // 通过spAcl类的set方法，将当前会话角色设置成该用户的角色
			$_SESSION["userinfo"] = $result; // 在SESSION中记录当前用户的信息
			return TRUE;
		} else {
			// 找不到匹配记录，用户名或密码错误，返回false
			return FALSE;
		}
	}

	/**
	 * 无权限提示及跳转
	 */
	public function acljump()
	{
		// 这里直接“借用”了spController.php的代码来进行无权限提示
		$url = FeiUrl("FeiTm", "login");
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"对不起，您没有权限进行此操作！\");window.location = 'http://www.grw.name';}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}

	public function check_url($url)
	{
		$con = array(
			'url' => $url
		);
		return $this->find($con);
	}

	public function getinfo()
	{
		$con = array(
			'id' => $_SESSION['Fei_Userid']
		);
		return $this->find($con);
	}
}