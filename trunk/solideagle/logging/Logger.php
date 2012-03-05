<?php

namespace Logging;

require_once 'Log.php';



use Log;

class Logger
{
	private static $conf = array('mode' => 0600, 'timeFormat' => '%X %x');
	private	static $logger = NULL;


	/**
	 * 
	 * 
	 * 
	 * @return Log
	 */
	static function getLogger()
	{
		if(Logger::$logger == NULL)
		Logger::$logger  = Log::singleton('file', 'out.log', 'ident', Logger::$conf);

		return Logger::$logger;
	}
}

?>