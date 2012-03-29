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
        
        if ($memberOfGroup != null && $r)
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
    
    // rename
    public static function renameGroup($newGroup, $oldGroup)
    {
        $connLdap = ConnectionLdap::singleton();           
        if ($connLdap->getConn() == null)
            return array(false, "LDAP Connectie mislukt");
        
        $info['objectClass'] = "group";
        $info["cn"] = $oldGroup->getName();
        
        $oldDn = "CN=" . $oldGroup->getName() . ",OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc;
        $rDn = "CN=" . $newGroup->getName();
        $r = ldap_rename($connLdap->getConn(), $oldDn, $rDn, null, true);

        return array($r,ldap_error($connLdap->getConn()));
    }
    
    public static function modifyGroup($group, $newParent, $newChildren, $oldParent, $oldChildren)
    {
        $connLdap = ConnectionLdap::singleton();           
        if ($connLdap->getConn() == null)
            return array(false, "LDAP Connectie mislukt");
        
        $dn = "OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc;
        
        $sr = ldap_search($connLdap->getConn(), $dn, "(CN=" . ConnectionLdap::escapeForLDAPSearch($group->getName()) . ")");
    	$groupInfo = ldap_get_entries($connLdap->getConn(), $sr);
        
        var_dump($groupInfo[0]['memberof']);
        // remove member in old parents
        $info['member'] = array();
        foreach($groupInfo[0]['memberof'] as $key => $parentDn) {
            if($key === 'count') continue;
            var_dump($parentDn);
            var_dump("CN=" . $oldParent->getName() . ",OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc);
            // remove member in parent
            if("CN=" . $oldParent->getName() . ",OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc == $parentDn)
            {
                var_dump("REMOVING PARENT" . $parentDn);
                $info['member'] = "CN=" . $group->getName() . "," . $dn;
                ldap_mod_del($connLdap->getConn(), $parentDn, $info);
            }
        }
        
        // remove old children
        foreach($oldChildren as $child) {
            if($key === 'count') continue;

            if(isset($groupInfo[0]['member']))
            {
                foreach($groupInfo[0]['member'] as $memberDn)
                {
                    if($memberDn == "CN=" . $child->getName() . ",OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc)
                    {
                        unset($info);
                        var_dump("REMOVING CHILD" . $child->getName());
                        $info['member'] = $memberDn;
                        ldap_mod_del($connLdap->getConn(), "CN=" . $group->getName() . ",OU=" . Config::$ad_groups_ou . "," . Config::$ad_dc, $info);
                    }
                }
            }
            else
            {
                echo($group->getName() . " did not have any child groups.");
            }
        }
        
        // add group to new parent
        if ($newParent != null)
        {
            unset($info);
            $info['member'] = "CN=" . $group->getName() . ", OU=" . Config::$ad_groups_ou . ", " . Config::$ad_dc;
            $dn = "CN=" . $newParent->getName() . ", OU=" . Config::$ad_groups_ou . ", " . Config::$ad_dc;
            $r = ldap_mod_add($connLdap->getConn(), $dn, $info);
            if (!$r)
            {
                Logger::log("Group cannot be added to group \"" . $newParent->getName() . "\"");
                return array($r,ldap_error($connLdap->getConn()));
            }
        }
        
        // add new children to group
        if ($newChildren != null)
        {
            foreach($newChildren as $child)
            {
                unset($info);
                $info['member'] = "CN=" . $child->getName() . ", OU=" . Config::$ad_groups_ou . ", " . Config::$ad_dc;
                $dn = "CN=" . $group->getName() . ", OU=" . Config::$ad_groups_ou . ", " . Config::$ad_dc;
                if (!ldap_mod_add($connLdap->getConn(), $dn, $info))
                {
                    Logger::log("Group \"" . $child->getName() . "\" cannot be added to group \"" . $group->getName() . "\"");
                    return array($r,ldap_error($connLdap->getConn()));
                }
            }
           
            if (!ldap_mod_add($connLdap->getConn(), $dn, $info))
            {
                Logger::log("Group cannot be added to group \"" . $newParent->getName() . "\"");
                return array($r,ldap_error($connLdap->getConn()));
            }
        }
    }
}

?>
