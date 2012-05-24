<?php

namespace solideagle;

class Config
{
	/*const mysqlserver = "mysql:host=10.3.7.102;dbname=CentralAccountDB;charset=utf8";
	const mysqluser = "dbuser";
	const mysqlpass = "ChaCha69";*/
	
	const mysqlserver = "mysql:host=triton;dbname=sleeuwaertm_solideagle;charset=utf8";
    const mysqluser = "sysSolidEagle";
    const mysqlpass = "phizei2Chiem3aeH3xahngair9laesiepiegoleGhoo7heek4rooquai5uha";
	
	
	
	private $configarr = array();
	
	public function __get($name)
	{
		if (isset($this->configarr[$name])) {
			return $this->configarr[$name];
		}else{
			return "CONFIG_NOT_SET";
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