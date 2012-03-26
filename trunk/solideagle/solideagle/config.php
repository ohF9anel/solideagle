<?php

namespace solideagle;

/* ----------GLOBAL CONFIG VARS---------- */

//define('Config::$ad_dc', 'DC=solideagle, DC=lok');
//define('AD_NETBIOS', 'SOLIDEAGLE');
//define('Config::$ad_dc_host', 'S1.solideagle.lok');
//define('Config::$ad_groups_ou', 'groepen');
//define('Config::$ad_users_ou', 'gebruikers');
//define('Config::$ad_ldaps_url', 'ldaps://S1.solideagle.lok');
//define('AD_USERNAME', 'Administrator@solideagle.lok');
//define('S1_ADMINISTRATOR', 'Administrator');
//define('Config::$ad_password', 'Azerty1234');
//define('Config::$ssh_server', 'S1.solideagle.lok');
//
//define('Config::$dir_name_downloads', '_downloads');
//define('Config::$dir_name_uploads', '_uploads');
//define('Config::$dir_name_scans', '_scans');
//define('Config::$dir_name_www', '_www');
//define('Config::$path_share_downloads', 'C:\downloads');
//define('Config::$path_share_uploads', 'C:\uploads');
//define('Config::$path_share_scans', 'C:\scans');
//define('Config::$path_share_www', 'C:\www');
//
//define('Config::$ss_ws_url', 'http://dbz-tmp.smartschool.be/Webservices/V3?wsdl');
//define('Config::$ss_ws_psw', '2CyeBGuSyc38R561');
/*TODO: Disable asserts here when deploying */

class Config
{
    static $ad_dc = 'DC=solideagle, DC=lok';
    static $ad_netbios  = 'SOLIDEAGLE';
    static $ad_dc_host = 'S1.solideagle.lok';
    static $ad_groups_ou = 'groepen';
    static $ad_users_ou = 'gebruikers';
    static $ad_ldaps_url = 'ldaps://S1.solideagle.lok';
    static $ad_username = 'Administrator@solideagle.lok';
    static $ad_administrator = 'Administrator';
    static $ad_password = 'Azerty1234';
    
    static $ssh_server = 'S1.solideagle.lok';
    static $dir_name_downloads = '_downloads';
    static $dir_name_uploads = '_uploads';
    static $dir_name_scans = '_scans';
    static $dir_name_www = '_www';
    static $path_share_downloads = 'C:\downloads';
    static $path_share_uploads = 'C:\uploads';
    static $path_share_scans = 'C:\scans';
    static $path_share_www = 'C:\www';
    
    static $ss_ws_url = 'http://dbz-tmp.smartschool.be/Webservices/V3?wsdl';
    static $ss_ws_psw = '2CyeBGuSyc38R561';

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