<?php

/**
 * QQ登陆
 * Class FeiQQlogin
 */
class FeiQQlogin
{
	const auth_code_url    = "https://graph.qq.com/oauth2.0/authorize";
	const access_token_url = "https://graph.qq.com/oauth2.0/token";
	const openid_url       = "https://graph.qq.com/oauth2.0/me";
	const user_info_url    = "https://graph.qq.com/user/get_user_info";
	#Config
	private $appid = '100457409';
	private $callback = "http://www.grw.name/admin.php?c=Grw&a=login";
	private $scope = "get_user_info";
	private $appkey = 'b560e1bb743eab2a6e177555de50405d';

	private $access_token, $openid;

	public function login()
	{
		$state                = md5(uniqid(rand(), TRUE));
		$_SESSION['qq_state'] = $state;

		$keysArr   = array(
			"response_type" => "code",
			"client_id"     => $this->appid,
			"redirect_uri"  => urlencode($this->callback),
			"state"         => $state,
			"scope"         => $this->scope
		);
		$login_url = $this->__combineURL(self::auth_code_url, $keysArr);
		header("Location:$login_url");
	}

	/**
	 * 获取Access_token
	 * @return $access_token
	 */
	public function callback()
	{
		$keysArr = array(
			"grant_type"    => "authorization_code",
			"client_id"     => $this->appid,
			"redirect_uri"  => urlencode($this->callback),
			"client_secret" => $this->appkey,
			"code"          => $_GET['code']
		);

		//------构造请求access_token的url
		$token_url = $this->__combineURL(self::access_token_url, $keysArr);
		$response  = $this->__Get_contents($token_url);

		if (strpos($response, "callback") !== FALSE) {
			$lpos     = strpos($response, "(");
			$rpos     = strrpos($response, ")");
			$response = substr($response, $lpos + 1, $rpos - $lpos - 1);
			$msg      = json_decode($response);
		}

		$params = array();
		parse_str($response, $params);
		$access_token       = $params['access_token'];
		$this->access_token = $access_token;
		return $access_token;
	}

	/**
	 * 获取openid
	 * @return $openid
	 */
	public function openid()
	{
		$keysArr   = array(
			"access_token" => $this->callback()
		);
		$graph_url = $this->__combineURL(self::openid_url, $keysArr);
		$response  = $this->__Get_contents($graph_url);

		//--------检测错误是否发生
		if (strpos($response, "callback") !== FALSE) {

			$lpos     = strpos($response, "(");
			$rpos     = strrpos($response, ")");
			$response = substr($response, $lpos + 1, $rpos - $lpos - 1);
		}

		$user = json_decode($response);
		if (isset($user->error)) {
			#error
			#$user->error $user->error_desription
		}
		$openid       = $user->openid;
		$this->openid = $openid;
		return $openid;
	}

	/**
	 * 获取用户基本信息
	 * @return string
	 */
	public function get_user_info()
	{
		$array             = array(
			"access_token"       => $this->access_token,
			"oauth_consumer_key" => $this->appid,
			"openid"             => $this->openid
		);
		$get_user_info_url = $this->__combineURL(self::user_info_url, $array);
		$user_info         = $this->__Get_contents($get_user_info_url);
		return $user_info;
	}

	/**
	 * combineURL
	 * 拼接url
	 * @param string $baseURL 基于的url
	 * @param array  $keysArr 参数列表数组
	 * @return string           返回拼接的url
	 */
	public function __combineURL($baseURL, $keysArr)
	{
		$combined = $baseURL . "?";
		$valueArr = array();

		foreach ($keysArr as $key => $val) {
			$valueArr[] = "$key=$val";
		}

		$keyStr = implode("&", $valueArr);
		$combined .= ($keyStr);

		return $combined;
	}

	/**
	 * get_contents
	 * 服务器通过get请求获得内容
	 * @param string $url 请求的url,拼接后的
	 * @return string           请求返回的内容
	 */
	public function __Get_contents($url)
	{
		if (ini_get("allow_url_fopen") == "1") {
			$response = file_get_contents($url);
		} else {
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
			curl_setopt($ch, CURLOPT_URL, $url);
			$response = curl_exec($ch);
			curl_close($ch);
		}

		return $response;
	}

	/**
	 * post
	 * post方式请求资源
	 * @param string $url     基于的baseUrl
	 * @param array  $keysArr 请求的参数列表
	 * @param int    $flag    标志位
	 * @return string           返回的资源内容
	 */
	public function __Post($url, $keysArr, $flag = 0)
	{
		$ch = curl_init();
		if (!$flag) curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $keysArr);
		curl_setopt($ch, CURLOPT_URL, $url);
		$ret = curl_exec($ch);

		curl_close($ch);
		return $ret;
	}
}