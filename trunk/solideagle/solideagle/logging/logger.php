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


		if (!$hidepathandline)
			$logplace = "File: " . $traces[1]["file"] . " Line: " . $traces[1]["line"] . "\n";


		self::getLogger()->log("\n" . $logplace . $errormessage . "\n", $messagetype);
	}
}

?>