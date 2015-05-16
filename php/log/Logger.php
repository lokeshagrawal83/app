<?php

	class Logger
	{
		const INFO = "Info";
		const WARNING = "Warning";
		const ERROR = "Error";
		//const APP_LOG = 												// Didn't use constant for log file name as static variables were not working.
		//const LOG = true;												// Can use constant for applicaton level logging on or off.	

		private $logFile;
		private $log = true;														// application level logging on or off.

		private function __construct()
		{
			$this->logFile = dirname(__FILE__)."\log.html";
		}

		public static function log($priority, $message, $log = true)				// third parameter $log is each log specific logging on or off.
		{
			$logger = new Logger();
			if($logger->log && $log)
			{
				error_log("[".date('d-M-Y H:i:s')."] PHP <b>".$priority.": </b>".$message."<br>", 3, $logger->logFile );
			}
		}
	}
?>