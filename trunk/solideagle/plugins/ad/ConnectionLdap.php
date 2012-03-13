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
        
        // attribute "memberOfGroups" not permitted in userInfo
        $memberOfGroups = $userInfo['memberOfGroups'];
        unset($userInfo['memberOfGroups']);

        $userInfo["useraccountcontrol"] = $enabled? "66048" : "66050";

        if (ldap_add($this->conn, $dn, $userInfo))
        {
            echo "Successfully added: " . $userInfo["cn"];
            
            // add user to correct group
            foreach($memberOfGroups as $memberOfGroup)
            {
                $group_name = "CN=" . $memberOfGroup->getName() . ",OU=groepen,DC=solideagle,DC=lok";
                
                $group_info['member'] = $dn; // User's DN is added to group's 'member' array
                ldap_mod_add($this->conn, $group_name, $group_info);
                echo "Added to group: " . $group_name;
            }
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
        
        $userInfo["homeDirectory"] = "\\\S1\shares\home\bodsonb";
        $userInfo["homeDrive"] = "T:";
        
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
