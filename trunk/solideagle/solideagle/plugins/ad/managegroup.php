<?php

namespace solideagle\plugins\ad;

use solideagle\Config;
use solideagle\logging\Logger;

class managegroup
{
    public static function addGroup($group, $memberOfGroup)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($connLdap->getConn() == null)
            return array(false, "LDAP Connectie mislukt");
        
        $info['objectClass'] = "group";
        $info["cn"] = $group->getName();
        
        $dn = "CN=" . $group->getName() . ",OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc;
        $r = ldap_add($connLdap->getConn(), $dn, $info);
        
        if ($memberOfGroup != null)
        {
            unset($info);
            $info['member'] = "CN=" . $group->getName() . ", OU=" . Config::$ad_groups_ou . ", " . Config::$ad_dc;
            $dn = "CN=" . $memberOfGroup->getName() . ", OU=" . Config::$ad_groups_ou . ", " . Config::$ad_dc;
            if (!ldap_mod_add($connLdap->getConn(), $dn, $info))
            {
                Logger::log("Group cannot be added to group \"" . $memberOfGroup->getName() . "\"");
                return array($r,ldap_error($connLdap->getConn()));
            }
        }

        return array($r,ldap_error($connLdap->getConn()));
    }
}

?>
