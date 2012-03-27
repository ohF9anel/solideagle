<?php

namespace solideagle\plugins\ad;

use solideagle\Config;

class managegroup
{
    public static function addGroup($group, $arrMemberOfGroups)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($connLdap->getConn() == null)
            return array(false, "LDAP Connectie mislukt");
        
        $info['objectClass'] = "group";
        $info["cn"] = $group->getName();
        foreach($arrMemberOfGroups as $)
        $info["member"] = 
        
        $dn = "CN=" . $group->getName() . ",OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc;
        $r = false;
        if ($arrMemberOfGroups == null)
        {
            $r = ldap_add($connLdap->getConn(), $dn, $info);
        }
        else 
        {
            
        }

        return array($r,ldap_error($connLdap->getConn()));
    }
}

?>
