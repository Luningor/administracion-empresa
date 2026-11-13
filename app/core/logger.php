<?php

namespace LBBAS\app\core;

class logger {
	static private ?logger $instance = NULL;
	private array $logs = [];

	/// @Constructor
	public function __construct() {}
	/// @Destructor
	public function __destruct() {}

	/// @Getters
	public static function get() : logger {
		logger::$instance = logger::$instance ?? new logger();
		return logger::$instance;
	}

	public function getLogs() : array {
		return $this->logs;
	}

	public function get_log_errors() : array {
		$errorlogs = [];
		foreach($this->logs as $logline)
			if($logline["level"] === "ERROR")
				$errorlogs[] = $logline;
		return $errorlogs;
	}

	public function get_log_warnings() : array {
		$warnlogs = [];
		foreach($this->logs as $logline)
			if($logline["level"] === "WARN")
				$warnlogs[] = $logline;
		return $warnlogs;
	}

	public function get_log_info() : array {
		$infologs = [];
		foreach($this->logs as $logline)
			if($logline["level"] === "INFO")
				$infologs[] = $logline;
		return $infologs;
	}

	/// @Methods
	public function log_error(string $message) {
		$this->logs[] = [
			"level" => "ERROR", 
			"time" => time(), 
			"msg" => $message
		];
	}

	public function log_warn(string $message)  {
		$this->logs[] = [
			"level" => "WARN", 
			"time" => time(), 
			"msg" => $message
		];
	}

	public function log_info(string $message)  {
		$this->logs[] = [
			"level" => "INFO", 
			"time" => time(), 
			"msg" => $message
		];
	}

}

?>