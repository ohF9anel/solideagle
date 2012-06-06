<?php

namespace solideagle\scripts;

use solideagle\Config;

class daemonrunner{

	public static function startDaemon()
	{
		
		shell_exec("nohup php ". __DIR__. "/daemon.php > /var/log/solideagle/daemon.log 2>&1 &");
	}
	
	public static function getDaemonStatus()
	{
		return shell_exec("cat /var/log/solideagle/daemon.log");
	}
	
}