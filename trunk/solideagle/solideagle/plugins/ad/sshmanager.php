<?php

namespace solideagle\plugins\ad;
use solideagle\Config;

require_once('Net/SSH2.php');
use solideagle\logging\Logger;

class SSHManager
{
	private  $_connections = array();
	private static $instance;
	
	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}
	
	
	public function getConnection($servername)
	{
		if(array_key_exists($servername,$this->_connections))
		{
			return $this->_connections[$servername];
		}
		
		
		$conn = new \Net_SSH2($servername);	
		if (!$conn->login(Config::$ad_administrator, Config::$ad_password))
		{
			Logger::getLogger()->log("Login to SSH failed on " . $servername);
			return null;
		}
		
	
		$this->_connections[$servername] = $conn;
		
		return $conn;
	}
	

	public function __destruct()
	{
		
		foreach($this->_connections as $key => $conn)
		{
			$conn->_close_channel(NET_SSH2_CHANNEL_SHELL);
			$conn->disconnect();
			
			echo $key . " closed";
		}

	}
	
}


?>