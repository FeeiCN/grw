<?php

/**
 * 用户前台页面
 * Class content
 */
class content extends FeiController
{
	function __construct()
	{
		parent::__construct();
		$siteurl           = "http://www.grw.name";
		$this->CSS_PATH    = $siteurl . "/assets/css/";
		$this->JS_PATH     = $siteurl . "/assets/js/";
		$this->FONT_PATH   = $siteurl . "/assets/font/";
		$this->IMG_PATH    = $siteurl . "/assets/img/";
		$this->SITEURL     = $siteurl;
		$this->STATICS     = $siteurl . "/themes/web3/";
		$this->ABOUTURL    = $siteurl . "/index.php?c=content&a=about";
		$this->LOGINURL    = $siteurl . "/index.php?c=content&a=login";
		$this->REGISTERURL = $siteurl . "/index.php?c=content&a=register";
		$this->SERVICESURL = $siteurl . "/index.php?c=content&a=services";
		$this->CONTACTURL  = $siteurl . "/index.php?c=content&a=contact";
		$this->PROJECTURL  = $siteurl . "/index.php?c=content&a=project";
		$this->BLOGURL     = $siteurl . "/index.php?c=content&a=blog";
	}

	function index()
	{
		if ($_SESSION['In_www']) {
			if (FeiClass('FeiAcl')->get() == 'Fei_Admin') {
				$this->login = TRUE;
			} else {
				$this->login = FALSE;
			}
			$this->display('index.html');
			exit;
		} else {
			//$theme_info = parse_ini_file(APP_PATH.'/themes/you/config.fei',true);
			//print_r($theme_info);
			//print_r($GLOBALS['G_Fei']);

			$this->PATH = "http://wufeifei.grw.name/themes/you/";
			//$this->gg = FeiClass('TemplateParse');
			$user = FeiClass('model_user');
			$url  = explode('.', $_SERVER['HTTP_HOST']);
			$url  = $url[0];

			if ($user->check_url($url)) {
				$info = $user->getinfo();
				//User Info
				$this->name        = $info['realname'];
				$this->position    = $info['position'];
				$this->email       = $info['email'];
				$this->address     = $info['address'];
				$this->phone       = $info['phone'];
				$this->qq          = $info['qq'];
				$this->birthday    = $info['birthday'];
				$this->description = $info['description'];
				//Find Conditions
				$userid     = $info['id'];
				$conditions = array('userid' => $userid);
				//Skill Info
				$skill        = FeiClass('model_skill');
				$skills       = $skill->findAll($conditions);
				$this->skills = $skills;
				//Work Info
				$work        = FeiClass('model_work');
				$w_c         = array('userid' => $userid);
				$works       = $work->findAll($conditions);
				$this->works = $works;
				//Education Info
				$education        = FeiClass('model_education');
				$educations       = $education->findAll($conditions);
				$this->educations = $educations;

				$this->display(APP_PATH . '/themes/you/index.html');
			} else {
				echo '你要访问的网站不存在或已关闭！';
			}
		}
	}

	function about()
	{
		$this->display('about.html');
	}

	function team()
	{
		$this->display('team.html');
	}

	function blog()
	{
		$this->display('blog.html');
	}

	function introduction()
	{
		$this->display('introduction.html');
	}

	function testimonials()
	{
		$this->display('testimonials.html');
	}

	function faq()
	{
		$this->display('faq.html');
	}

	function themes()
	{
		$this->display('themes.html');
	}

	function logs()
	{
		$this->display('logs.html');
	}

	// In_www

	function login()
	{
		$this->display('login.html');
	}

	function register()
	{
		$this->display('register.html');
	}

	function services()
	{
		$this->display('services.html');
	}

	function contact()
	{
		$this->display('contact.html');
	}

	function project()
	{
		$this->display('project.html');
	}
}