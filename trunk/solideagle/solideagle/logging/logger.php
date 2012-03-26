<?php

namespace solideagle\logging;

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
		{
			if (strtoupper(substr(PHP_OS, 0, 3)) === 'WIN') {
			  	Logger::$logger  = Log::singleton('file', 'c:\out.log', 'ident', Logger::$conf);
			} else {
			   	Logger::$logger  = Log::singleton('file', '/tmp/out.log', 'ident', Logger::$conf);
			}

		}
	

		return Logger::$logger;
	}
}

?>