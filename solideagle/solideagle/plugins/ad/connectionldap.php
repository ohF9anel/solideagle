<?php

namespace solideagle\plugins\ad;

use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\logging\Logger;
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
    
    
    /**
     *
     * @param str $s    subject string
     * @param bool $d   DN mode
     * @param mixed $i  chars to ignore
     * @return type
     */
    public static function ldap_escape ($s, $d = FALSE, $i = NULL)
    {
    	$m = ($d) ? array(1 => '\\',',','=','+','<','>',';','"','#') : array(1 => '\\','*','(',')',chr(0));
    	if (is_string($i) && ($l = strlen($s))) {
    		for ($n = 0; $n < $l; $n++) if ($k = array_search(substr($s,$n,1),$m)) unset($m[$k]);
    	}
    	else if (is_array($i)) foreach ($i as $c) if ($k = array_search($c,$m)) unset($m[$k]);
    	$q = array();
    	foreach ($m as $k => $c) $q[$k] = '\\'.str_pad(dechex(ord($c)),2,'0',STR_PAD_LEFT);
    	return str_replace($m,$q,$s);
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
