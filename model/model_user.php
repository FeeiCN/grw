<?php
class model_user extends FeiModel{
    var $pk = "id";
    var $table = "user";

	public function userlogin($uname, $upass){ 
		$conditions = array(
			'uname' => $uname,
			'upass' => $upass,
		);
		if( $result = $this->find($conditions) ){
			spClass('FeiAcl')->set($result['acl']);
			$_SESSION["userinfo"] = $result;
			return true;
		}else{
			return false;
		}
	}
	/**
	 * 无权限提示及跳转
	 */
	public function acljump(){ 
		$url = FeiUrl("FeiTm","login");
		echo "<html><head><meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\"><script>function sptips(){alert(\"对不起，您没有权限进行此操作！\");window.location = 'http://www.grw.name';}</script></head><body onload=\"sptips()\"></body></html>";
		exit;
	}

	public function check_url($url){
		$con  = array(
			'url'=>$url
		);
		return $this->find($con);
	}

	/**
	 * 获取当前用户信息
	 * @return bool|mixed
	 */
	public function getCurrentUserInfo(){
		$con = array(
			'id'=>$_SESSION['Fei_Userid']
		);
		$userInfo = $this->find($con);
		$userInfo['roleName'] = FeiClass('model_user_role')->getNameById($userInfo['roleId']);
		return $userInfo;
	}
}