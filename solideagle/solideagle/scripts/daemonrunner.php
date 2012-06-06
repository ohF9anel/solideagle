<?php

namespace solideagle\scripts;

use solideagle\Config;

class daemonrunner{

	public static function startDaemon()
	{
		//echo "nohup php ". __DIR__. "/daemon.php > /var/log/solideagle/daemon.log 2>&1 & echo $!";
		
		echo shell_exec( __DIR__. "/startdaemon.sh");
	}
	
	public static function getDaemonStatus()
	{
		return shell_exec("cat /var/log/solideagle/daemon.log");
	}
	
}