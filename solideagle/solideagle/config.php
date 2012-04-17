<?php

namespace solideagle;

class Config
{
	private $configarr = array();
	
	public function __get($name)
	{
		if (array_key_exists($name, $this->configarr)) {
			return $this->configarr[$name];
		}
	}
	
	private static $instance;
	
	private function __construct()
	{	
		
		
	}
	
	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}
	
	
    static $ad_dc = 'DC=solideagle, DC=lok';
    static $ad_dns = 'solideagle.lok';
    static $ad_netbios  = 'SOLIDEAGLE';
    static $ad_dc_host = 'S1.solideagle.lok';
    static $ad_groups_ou = 'groepen'; //waar security groepen komen
    static $ad_ldaps_url = 'ldaps://S1.solideagle.lok';
    static $ad_administrator = 'SYS_Solideagle';
    static $ad_username = 'SYS_Solideagle@solideagle.lok';
    static $ad_password = 'ChaCha69';
    
    static $ssh_server = 'S1.solideagle.lok';
    static $dir_name_downloads = '_downloads';
    static $dir_name_uploads = '_uploads';
    static $dir_name_scans = '_scans';
    static $dir_name_www = '_www';
    static $path_share_downloads = 'C:\downloads';
    static $path_share_uploads = 'C:\uploads';
    static $path_share_scans = 'C:\scans';
    static $path_share_www = 'C:\www';
    static $path_homefolders = "C:\homefolders";
    
    static $ss_ws_url = 'http://dbz-tmp.smartschool.be/Webservices/V3?wsdl';
    static $ss_ws_psw = '2CyeBGuSyc38R561';
    
    const mysqlserver = "mysql:host=10.3.7.102;dbname=CentralAccountDB;charset=utf8";
    const mysqluser = "dbuser";
    const mysqlpass = "ChaCha69";
    
    /*const mysqlserver = "mysql:host=localhost;dbname=CentralAccountDB;charset=utf8";
    const mysqluser = "root";
    const mysqlpass = "root";*/

}

// Active assert and make it quiet
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

// Create a handler function
function my_assert_handler($file, $line, $code)
{
	echo "Assertion Failed:\r\n
        File '$file'\r\n
        Line '$line'\r\n
        Code '$code'\r\n";
}

// Set up the callback
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

/*----------------------------------*/

?>