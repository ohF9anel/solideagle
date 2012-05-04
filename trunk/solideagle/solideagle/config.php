<?php

namespace solideagle;

class Config
{
	const mysqlserver = "mysql:host=10.3.7.102;dbname=CentralAccountDB;charset=utf8";
	const mysqluser = "dbuser";
	const mysqlpass = "ChaCha69";
	
	private $configarr = array();
	
	public function __get($name)
	{
		//isset gives better performance but does not return true on NULL values, so we add array key exists which is slower
		//but will rarely be called due to short circuiting
		if (isset($this->configarr[$name]) || array_key_exists($name, $this->configarr)) {
			return $this->configarr[$name];
		}
	}
	
	private static $instance;
	
	private function __construct()
	{	
		$this->configarr = \solideagle\data_access\Config::getConfig();
	}
	
	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}
}

?>