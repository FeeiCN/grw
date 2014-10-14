<?php

/**
 * 云盘（待废弃）
 * Class yunpan
 */
class yunpan extends Grw
{
	function __construct()
	{
		parent::__construct();
	}

	function index()
	{

	}

	function api()
	{
		include_once APP_PATH . '/lib/elFinderConnector.class.php';
		include_once APP_PATH . '/lib/elFinder.class.php';
		include_once APP_PATH . '/lib/elFinderVolumeDriver.class.php';
		include_once APP_PATH . '/lib/elFinderVolumeLocalFileSystem.class.php';
		include_once APP_PATH . '/lib/elFinderVolumeMySQL.class.php';

		$id     = FeiClass('model_yunpan')->index();
		$option = array(
			// 'debug' => true,
			'roots' => array(
				array(
					'driver'        => 'MySQL', // driver for accessing file system (REQUIRED)
					'path'          => $id, // path to files (REQUIRED)
					'accessControl' => 'access', // disable and hide dot starting files (OPTIONAL)
					'uploadMaxSize' => '10000K',
					//'uploadAllow'=>array('doc','xls','ppt','txt','swf','sql','image'),
					'acceptedName'  => ''
				)
			)
		);
		//print_r($opts);exit;

		$connector = new elFinderConnector(new elFinder($option));
		$connector->run();
	}

	function access($attr, $path, $data, $volume)
	{
		return strpos(basename($path), '.') === 0 // if file/folder begins with '.' (dot)
			? !($attr == 'read' || $attr == 'write') // set read+write to false, other (locked+hidden) set to true
			: NULL; // else elFinder decide it itself
	}
}