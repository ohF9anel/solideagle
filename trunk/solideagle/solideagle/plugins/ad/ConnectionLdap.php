<?php

namespace solideagle\plugins\ad;

use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\data_access\logging\Logger;
use solideagle\Config;

class ConnectionLDAP
{
    
    private static $instance;
    private $conn;

    public function __construct()
    {
        $this->conn = ldap_connect(Config::$ad_ldaps_url);
        if ($this->conn == null)
             Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nConnection to AD cannot be made.", PEAR_LOG_ERR);
        
        // bind to the LDAP server specified above 
        if (!ldap_bind($this->conn, Config::$ad_username, Config::$ad_password))
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nCould not bind to AD server with given credentials.", PEAR_LOG_ERR);  

        ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->conn, LDAP_OPT_REFERRALS, 0);
    }
    
    public function __destruct()
    {
        // all done? clean up
        ldap_close($this->conn);
    }
    
    public static function singleton()
    {
            if (!isset(self::$instance)) {
                    $className = __CLASS__;
                    self::$instance = new $className;
            }
            return self::$instance;
    }
    
    public function getConn()
    {
    
        return $this->conn;
    }
    
    public static function escapeForLDAPSearch($stringtoescape)
    {
    	$stringtoescape = str_replace("*","\*",$stringtoescape);
    	$stringtoescape = str_replace("(","\(",$stringtoescape);
    	$stringtoescape = str_replace(")","\)",$stringtoescape);
    	$stringtoescape = str_replace('\\','\\\\',$stringtoescape);
    	return $stringtoescape;
    }
}

?>
