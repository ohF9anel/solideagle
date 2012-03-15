<?php

namespace AD;

require_once 'data_access/Person.php';
require_once 'data_access/Group.php';
require_once 'config.php';
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
        $groups = $userInfo['groups'];
        unset($userInfo['groups']);

        $userInfo["useraccountcontrol"] = $enabled? "66048" : "66050";

        if (ldap_add($this->conn, $dn, $userInfo))
        {
            echo "Successfully added: " . $userInfo["cn"];
            
            $this->addUserToGroups($groups, $dn);
        }
    }
    
    public function addUserToGroups($groups, $dn)
    {
        // add user to correct group
        foreach($groups as $group)
        {
            $group_name = "CN=" . $group->getName() . ",OU=groepen,DC=solideagle,DC=lok";

            $group_info['member'] = $dn;
            ldap_mod_add($this->conn, $group_name, $group_info);
            echo "Added to group: " . $group_name;
        }
    }
    
    public function updateUser($userInfo, $dn, $enabled = true)
    {
        if ($this->conn == null)
            return false;
        
        $userInfo['objectclass'] = "user";
        
        // cn not permitted to update
        unset($userInfo["cn"]);
        
        // attribute "memberOfGroups" not permitted in userInfo
        $groups = $userInfo['groups'];
        unset($userInfo['groups']);
        
        $sr = ldap_search($this->conn, $dn, "(uid=" . $userInfo['uid'] . ")");
        $ent = ldap_get_entries($this->conn, $sr);
        
        $ac = $ent[0]["useraccountcontrol"][0];

        $disable = ($ac |  2); // set all bits plus bit 1 (=dec2)
        $enable = ($ac & ~2); // set all bits minus bit 1 (=dec2)
 
        $userInfo["useraccountcontrol"] = $enabled? $enable : $disable;
        
        // remove previous group memberships
        if(isset($ent[0]["memberof"]))
        {
            for($i = 0; $i < sizeof($ent[0]["memberof"]) - 1; $i++)
            {
                $group = $ent[0]["memberof"][$i];
                $group_info['member'] = array();
                ldap_mod_del($this->conn, $group, $group_info);
            }
        }
        
        if (ldap_modify($this->conn, $dn, $userInfo))
        {
            echo "Successfully updated";
            
            $this->addUserToGroups($groups, $dn);
        }
    }
    
    public static function delUserByUsername($userName)
    {
        
    }
    
    public function addOU($arrParentsGroups, $childGroup)
    {
        if ($childGroup == null)
            return false;
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = $childGroup->getName();
        if ($arrParentsGroups == null)
        {
            $r = ldap_add($this->conn, "OU=" . $childGroup->getName() . ", " . AD_DC, $info);
        }
        else
        {
            $ouString = "";
            for($i = 0; $i < sizeof($arrParentsGroups); $i++)
            {
                $ouString .= "OU=" . $arrParentsGroups[$i]->getName() . ", ";
            }
            $r = ldap_add($this->conn, "OU=" . $childGroup->getName() . ", " . $ouString . AD_DC, $info);
        }
        
        

        // add data to directory
        //$r = ldap_add($this->conn, "OU=geb,OU=gebruikers," . AD_DC, $info);
    }
    
}

?>
