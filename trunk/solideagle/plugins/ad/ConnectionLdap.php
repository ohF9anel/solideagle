<?php

namespace AD;

require_once 'data_access/Person.php';
require_once 'test/config.php';
use DataAccess\Person;


class ConnectionLDAP
{
    
    private $conn;
    
    public function __construct()
    {
        //ldap_start_tls();
        $this->conn = ldap_connect('ldaps://S1.solideagle.lok') or die("Could not connect to server");  
        // bind to the LDAP server specified above 
        $r = ldap_bind($this->conn, AD_USERNAME, AD_PASSWORD) or die("Could not bind to server");     

        ldap_set_option($this->conn, LDAP_OPT_PROTOCOL_VERSION, 3);
        ldap_set_option($this->conn, LDAP_OPT_REFERRALS, 0);
    }
    
    public function __destruct()
    {
        // all done? clean up
        ldap_close($this->conn);
    }
    
    public function addUser($userInfo, $dn, $enabled = true)
    {
        if ($this->conn == null)
            return false;

        $userInfo["objectclass"] = "user";

        $userInfo["useraccountcontrol"] = $enabled? "66048" : "66050";

        if (ldap_add($this->conn, $dn, $userInfo))
        {
            echo "Successfully added: " . $userInfo["cn"];
        }
    }
    
    public function updateUser($userInfo, $dn, $enabled = true)
    {
        if ($this->conn == null)
            return false;
        
        $userInfo['objectclass'] = "user";
        unset($userInfo["cn"]);
        
        $sr = ldap_search($this->conn, $dn, "(uid=" . $userInfo['uid'] . ")");
        $ent = ldap_get_entries($this->conn, $sr);
        
        $ac = $ent[0]["useraccountcontrol"][0];

        $disable = ($ac |  2); // set all bits plus bit 1 (=dec2)
        $enable = ($ac & ~2); // set all bits minus bit 1 (=dec2)
 
        $userInfo["useraccountcontrol"] = $enabled? $enable : $disable;
        
        if (ldap_modify($this->conn, $dn, $userInfo))
        {
            echo "Successfully updated";
        }
    }
    
    public static function delUserByUsername($userName)
    {
        
    }
    
}

?>
