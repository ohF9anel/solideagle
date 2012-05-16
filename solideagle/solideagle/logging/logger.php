<?php

namespace solideagle\logging;

use solideagle\Config;

use \Log;

require_once('Log.php');

class Logger
{
	private static $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
	private	static $logger = NULL;

	/**
	 *
	 * @return Log
	 */
	private static function getLogger()
	{
		
		if(!file_exists("/var/log/solideagle/out.log"))
		{
			exec("touch /var/log/solideagle/out.log");
			exec("chmod 777 /var/log/solideagle/out.log");
			
			self::log("Created log file");
		}
		
		$loglevel = Config::singleton()->debugLevel;

		if(Logger::$logger == NULL)
		{
			Logger::$logger = &Log::singleton('file', '/var/log/solideagle/out.log', 'ident', Logger::$conf,$loglevel);
		}

		return Logger::$logger;
	}

	public static function log($errormessage,$messagetype = PEAR_LOG_ERR,$hidepathandline = false)
	{
		$traces = debug_backtrace();

		$logplace = "";


		//if (!$hidepathandline)
			$logplace = "Class: " . $traces[1]["class"]. " Function: " . $traces[1]["function"] . " Line: " . $traces[0]["line"] . "\n";


		self::getLogger()->log("\n" . $logplace . $errormessage . "\n", $messagetype);
	}
}

?>