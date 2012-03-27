<?php

namespace solideagle\plugins\ad;

use solideagle\data_access\Group;
use solideagle\logging\Logger;
use solideagle\Config;

class ManageUser
{
    public static function addUser($userInfo, $arrParentsOUs)
    {
        $connLdap = ConnectionLDAP::singleton();
        
        if ($connLdap->getConn() == null)
            return false;
        
        // every user should have a parent ou, because root ou is gebruikers
        if ($arrParentsOUs == null)
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \n" . var_export($arrParentsOUs, true) . "\nuser has no parent groups, every user should be at least child of root (gebruikers).", PEAR_LOG_ERR);
            return false;
        }

        $userInfo["objectclass"] = "user";
        $userInfo["useraccountcontrol"] = $userInfo['enabled'] ? "66048" : "66050";
        
//        $group = $userInfo['groups'][0];
        
        // attribute "groups" and "enabled" not permitted in userInfo
        $groups = $userInfo['groups'];
        unset($userInfo['groups']);
        unset($userInfo['enabled']);
        
        foreach($userInfo as $key => $attr)
        {
            if ($attr == '') {
                unset($userInfo[$key]);
            }
        }
        
        $dn = "CN=" . $userInfo['cn'] . ", OU=" . $groups[0]->getName() . ", ";
        for($i = 0; $i < sizeof($arrParentsOUs); $i++)
        {
            $dn .= "OU=" . $arrParentsOUs[$i]->getName() . ", ";
        }
        
        $dn .= Config::$ad_dc;
        $ret = ldap_add($connLdap->getConn(), $dn, $userInfo);
        if ($ret)
        {
            ManageUser::addUserToGroups($groups, $dn);
        }
        else 
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \n" . var_export($userInfo, true) . "\nReason: " . var_export(array($ret, ldap_error($connLdap->getConn())), true), PEAR_LOG_ERR);
        }

        return array($ret, ldap_error($connLdap->getConn()));
    }
    
    public static function addUserToGroups($groups, $dn)
    {
        $connLdap = ConnectionLdap::singleton();
        
        if ($connLdap->getConn() == null)
            return false;
        
        $ret = true;
        
        // add user to correct group
        foreach($groups as $group)
        {
            $group_name = "CN=" . $group->getName() . ", OU=" . Config::$ad_groups_ou . ", " . Config::$ad_dc;
            $group_info['member'] = $dn;
            if (!ldap_mod_add($connLdap->getConn(), $group_name, $group_info))
            {
                Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nUser cannot be added to group \"" . $group_name . "\"", PEAR_LOG_ERR);
                $ret = false;
            }
        }
        
        return $ret;
    }
    
    public static function updateUser($userInfo, $arrParentsOUs)
    {
        $connLdap = ConnectionLDAP::singleton();
        
        if ($connLdap->getConn() == null)
            return false;
        
        $userInfo["objectclass"] = "user";
        $userInfo["useraccountcontrol"] = $userInfo['enabled'] ? "66048" : "66050";
        
        $group = $userInfo['groups'][0];
        $groups = $userInfo['groups'];
        
        $parentDn = "OU=" . $group->getName() . ", ";
        for($i = 0; $i < sizeof($arrParentsOUs); $i++)
        {
            $parentDn .= "OU=" . $arrParentsOUs[$i]->getName() . ", ";
        }

        $parentDn .= Config::$ad_dc;
        $dn = "CN=" . $userInfo['cn'] . ", " . $parentDn;
        
        $sr = ldap_search($connLdap->getConn(), "OU=" . Config::$ad_users_ou . ", " . Config::$ad_dc, "(uid=" . $userInfo['uid'] . ")");
        $oldUserInfo = ldap_get_entries($connLdap->getConn(), $sr);
        
        if (!isset($oldUserInfo[0]))
        {
            Logger::getLogger()->log("User \"" . $userInfo['uid'] . "\" trying to update in AD not found in: \"OU=" . Config::$ad_users_ou . ", " .Config::$ad_dc. "\".",PEAR_LOG_ERR);
            return false;
        }
        // move user to other ou?
        if ($oldUserInfo[0]['distinguishedname'][0] != $dn)
        {
            ldap_rename($connLdap->getConn(), $oldUserInfo[0]['distinguishedname'][0], 'CN=' . $oldUserInfo[0]['cn'][0], $parentDn, true);
        }
        
        $ac = $oldUserInfo[0]["useraccountcontrol"][0];

        $disable = ($ac |  2); // set all bits plus bit 1 (=dec2)
        $enable = ($ac & ~2); // set all bits minus bit 1 (=dec2)
 
        $userInfo["useraccountcontrol"] = $userInfo['enabled'] ? $enable : $disable;
        
        // remove previous group memberships
        if(isset($oldUserInfo[0]["memberof"]))
        {
            for($i = 0; $i < sizeof($oldUserInfo[0]["memberof"]) - 1; $i++)
            {
                $group = $oldUserInfo[0]["memberof"][$i];
                $group_info['member'] = array();
                ldap_mod_del($connLdap->getConn(), $group, $group_info);
            }
        }
        
        // attribute "groups" and "enabled" and "cn" not permitted in userInfo   
        unset($userInfo['groups']);
        unset($userInfo['enabled']);
        unset($userInfo['cn']);
        
        $ret = ldap_modify($connLdap->getConn(), $dn, $userInfo);
        if ($ret)
        {
            ManageUser::addUserToGroups($groups, $dn);
        }
        else
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \n
                " . var_export($userInfo, true) . "\n: user cannot be modified", PEAR_LOG_ERR);
        }
        
        return array($ret, ldap_error($connLdap->getConn()));
        
    }
    
    public static function delUser($userName, $arrParentsOUs)
    {
        
    }
}

?>
