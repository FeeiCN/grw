<?php
/**
 * 模板解析
 * Class TemplateParse
 */
class TemplateParse
{
	/**
	 * 模板引擎实例
	 */
	public $engine = NULL;
	/**
	 * 模板是否已输出
	 */
	public $displayed = FALSE;

	/**
	 * 网站信息
	 */
	public $site = NULL;

	/**
	 * 构造函数，进行模板引擎的实例化操作
	 */
	public function __construct()
	{
		if (FALSE == $GLOBALS['G_Fei']['view']['enabled']) return FALSE;
		if (FALSE != $GLOBALS['G_Fei']['view']['auto_ob_start']) ob_start();
		$this->engine = FeiClass($GLOBALS['G_Fei']['view']['engine_name'], NULL, $GLOBALS['G_Fei']['view']['engine_path']);
		if ($GLOBALS['G_Fei']['view']['config'] && is_array($GLOBALS['G_Fei']['view']['config'])) {
			$engine_vars = get_class_vars(get_class($this->engine));
			foreach ($GLOBALS['G_Fei']['view']['config'] as $key => $value) {
				if (array_key_exists($key, $engine_vars)) $this->engine->{$key} = $value;
			}
		}
		if (!empty($GLOBALS['G_Fei']['Fei_app_id']) && isset($this->engine->compile_id)) $this->engine->compile_id = $GLOBALS['G_Fei']['Fei_app_id'];
		// 检查编译目录是否可写
		if (empty($this->engine->no_compile_dir) && (!is_dir($this->engine->compile_dir) || !is_writable($this->engine->compile_dir))) __mkdirs($this->engine->compile_dir);
		FeiAddViewFunction('Copyright', array('TemplateParse', '__template_Copyright'));
		FeiAddViewFunction('Title', array('TemplateParse', '__template_Title'));
		FeiAddViewFunction('Keywords', array('TemplateParse', '__template_Keywords'));
		FeiAddViewFunction('Description', array('TemplateParse', '__template_Description'));
		FeiAddViewFunction('Author', array('TemplateParse', '__template_Author'));
		FeiAddViewFunction('Category', array('TemplateParse', '__template_Category'));

		$this->site    = FeiClass('model_site')->find();
		$this->contact = FeiClass('model_contact')->find();
	}

	/**
	 * SITE INFO
	 */
	public function __template_Copyright()
	{
		return 'Copyright 2004-2012 <a href=\'http://www.feei.cn\'>Feei.</a> All Rights Reserved';
	}

	public function __template_Title()
	{
		return $this->site['title'];
	}

	public function __template_Keywords()
	{
		return $this->site['keywords'];
	}

	public function __template_Description()
	{
		return $this->site['description'];
	}

	public function __template_Author()
	{
		return $this->site['author'];
	}

	public function __template_Icp()
	{
		return $this->site['icp'];
	}

	/**
	 * CONTACT INFO
	 */
	public function __template_Company()
	{
		return $this->contact['company'];
	}

	public function __template_Linkman()
	{
		return $this->contact['linkman'];
	}

	public function __template_Phone()
	{
		return $this->contact['phone'];
	}

	public function __template_Tel()
	{
		return $this->contact['tel'];
	}

	public function __template_Fax()
	{
		return $this->contact['fax'];
	}

	public function __template_address()
	{
		return $this->contact['address'];
	}

	public function __template_email()
	{
		return $this->contact['email'];
	}

	public function __template_Category($params)
	{
		$category   = FeiClass(model_category);
		$result     = $category->findBy('letter', $params[letter]);
		$conditions = array('parentid' => $result[catid]);
		$result     = $category->findAll($conditions);
		return $result;
	}
}