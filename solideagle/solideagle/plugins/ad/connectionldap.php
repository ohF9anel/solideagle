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

    private function __construct()
    {
        $this->conn = ldap_connect('ldaps://10.3.7.111');
        
        // try anonymous login to test connection
        $anon = @ldap_bind($this->conn);
        if (!$anon) {
            $this->conn = null;
            // test connection failed
            Logger::log("Connection to AD cannot be made on: " . Config::$ad_dc_host);
        }
        else {
            // test passed
            // bind to the LDAP server specified above 
            ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
            ldap_set_option($this->conn, LDAP_OPT_REFERRALS, 0);
            
            if (!ldap_bind($this->conn, Config::$ad_username, Config::$ad_password))
            {
                $this->conn = null;
                Logger::log("Could not bind to AD server with given credentials.");  
            }
        } 
    }
    
    public function __destruct()
    {
        if ($this->conn != null)
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
