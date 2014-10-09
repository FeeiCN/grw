<?php

class FeiLog
{

	private $severityLevel = array(
		'ERROR',
		'WARN',
		'NOTICE',
		'INFO',
		'DEBUG',
	);

	private $LOG_FILE_SIZE = '10240000'; // Log File Size
	private $LOG_FILE_PATH = 'tmp'; // Log File Path
	private $LOG_FILE_PREFIX = '.access.'; // Log File Prefix, for example: temp/.access_error.log
	private $LOG_SEND_MAIL = NULL; // Send the Log by Email: NULL not send, ERROR send ERROR, ALL send ALL.
	private $LOG_MAIL_TO; // Send the mail to

	public function __construct()
	{
		$params                = FeiExt('FeiLog');
		$this->LOG_FILE_SIZE   = isset($params['logsize']) ? $params['logsize'] : $this->LOG_FILE_SIZE;
		$this->LOG_FILE_PATH   = isset($params['logpath']) ? $params['logpath'] : $this->LOG_FILE_PATH;
		$this->LOG_FILE_PREFIX = isset($params['logprefix']) ? $params['logprefix'] : $this->LOG_FILE_PREFIX;
		$this->LOG_SEND_MAIL   = isset($params['mail']) ? $params['mail'] : $this->LOG_SEND_MAIL;
		$this->LOG_MAIL_TO     = isset($params['mailto']) ? $params['mailto'] : $_SERVER["SERVER_ADMIN"];
	}

	public function __call($level, $args)
	{
		if (!in_array(strtoupper($level), $this->severityLevel)) $level = 'NOTICE';
		$log = $this->log($args[0], strtoupper($level));
		$this->write($log, strtolower($level));
		if ("ALL" == $this->LOG_SEND_MAIL || in_array(strtoupper($this->LOG_SEND_MAIL), $this->severityLevel))
			$this->mail($log, strtoupper($level));
	}

	private function log($msg, $severityLevel = 'DEBUG')
	{
		Global $__controller, $__action;
		$backtrace      = debug_backtrace();
		$caller         = $backtrace[2];
		$data           = date("Y-m-d") . " " . date("H:i:s") . " " . $_SERVER['REMOTE_ADDR'];
		$IPLength       = strlen($_SERVER['REMOTE_ADDR']);
		$numWhitespaces = 15 - $IPLength;
		for ($i = 0; $i < $numWhitespaces; $i++) $data .= " ";
		$data .= " $__controller@$__action ";
		$data .= $caller['file'] . " on line " . $caller['line'] . "\t";
		$data .= $severityLevel . ": " . $msg . "\r\n";
		return $data;
	}

	private function write($log, $severityLevel)
	{
		$destination = rtrim($this->LOG_FILE_PATH, "\\/") . '/';
		__mkdirs($destination);
		$backfix = '.log';
		$destination .= $this->LOG_FILE_PREFIX . $severityLevel;
		if (is_file($destination . $backfix) && floor($this->LOG_FILE_SIZE) <= filesize($destination . $backfix))
			rename($destination . $backfix, dirname($destination) . '/' . basename($destination) . '.' . time() . $backfix);
		$handle = fopen($destination . $backfix, "at+");
		fwrite($handle, $log);
		fclose($handle);
	}

	private function mail($log, $severityLevel)
	{
		$to      = $this->LOG_MAIL_TO;
		$subject = $severityLevel . " " . date("Y-m-d") . " " . date("H:i:s");
		$message = $log;
		$headers = 'From: ' . $_SERVER["SERVER_ADMIN"] . "\r\n" .
			'Reply-To: ' . $_SERVER["SERVER_ADMIN"] . "\r\n" .
			'X-Mailer: PHP/' . phpversion();
		@mail($to, $subject, $message, $headers);
	}
}

?>