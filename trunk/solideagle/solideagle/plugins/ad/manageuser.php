<?php

namespace solideagle\plugins\ad;

use solideagle\data_access\Group;
use solideagle\logging\Logger;
use solideagle\Config;
use solideagle\plugins\StatusReport;

class ManageUser
{
    public static function addUser($userInfo, $arrParentsOUs)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");
        
        // every user should have a parent ou, because root ou is gebruikers
        /*if ($arrParentsOUs == null)
        {
            Logger::log(var_export($arrParentsOUs, true) . "\nuser has no parent groups, every user should be at least child of root (gebruikers).");
            return new StatusReport(false,"user has no parents group (OU does not exist?)");
        }*/

        $userInfo["objectclass"] = "user";
        $userInfo["useraccountcontrol"] = $userInfo['enabled'] ? "66048" : "66050";

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
        
        $dn .= Config::singleton()->ad_dc;
        $r = ldap_add($connLdap->getConn(), $dn, $userInfo);
        if ($r)
        {
            ManageUser::addUserToGroups($groups, $dn);
        }
        else 
        {
            Logger::log("User: " . var_export($userInfo, true) . " cannot be added in: " . $dn);
        }
        
        return new StatusReport($r, ldap_error($connLdap->getConn()));
    }
    
    public static function addUserToGroups($groups, $dn)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");
        
        $r = true;
        
        // add user to correct group
        foreach($groups as $group)
        {
            $group_name = "CN=" . $group->getName() . ", OU=" . Config::singleton()->ad_groups_ou . ", " . Config::singleton()->ad_dc;
            $group_info['member'] = $dn;
            if (!ldap_mod_add($connLdap->getConn(), $group_name, $group_info))
            {
                Logger::log("User cannot be added to group: \"" . $group_name . "\"");
                $r = false;
            }
        }
        
        return new StatusReport($r,ldap_error($connLdap->getConn()));
    }
    
    public static function updateUser($userInfo, $arrParentsOUs)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");
        
        $userInfo["objectclass"] = "user";
        $userInfo["useraccountcontrol"] = $userInfo['enabled'] ? "66048" : "66050";
        
        $group = $userInfo['groups'][0];
        $groups = $userInfo['groups'];
        
        $parentDn = "OU=" . $group->getName() . ",";
        for($i = 0; $i < sizeof($arrParentsOUs); $i++)
        {
            $parentDn .= "OU=" . $arrParentsOUs[$i]->getName() . ",";
        }

        $parentDn .= Config::singleton()->ad_dc;
        $dn = "CN=" . $userInfo['cn'] . "," . $parentDn;
        
        // get old userinfo
        $sr = ldap_search($connLdap->getConn(), Config::singleton()->ad_dc, "(uid=" . $userInfo['uid'] . ")");
        $oldUserInfo = ldap_get_entries($connLdap->getConn(), $sr);
        
        if (!isset($oldUserInfo[0]))
        {
            Logger::log("User \"" . $userInfo['uid'] . "\" trying to update in AD not found in: \"" . Config::singleton()->ad_dc. "\".");
            return false;
        }
        
        // rename dn?
        if ($oldUserInfo[0]['distinguishedname'][0] != $dn)
            ldap_rename($connLdap->getConn(), $oldUserInfo[0]['distinguishedname'][0], 'CN=' . $userInfo['cn'], $parentDn, true);
        
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
        
        // password updates not allowed here
        unset($userInfo['unicodePwd']);
        
        // unset empty values
        foreach($userInfo as $key => $attr)
        {
            if ($attr == '' || $attr == null) {
                unset($userInfo[$key]);
            }
        }

        $ret = ldap_modify($connLdap->getConn(), $dn, $userInfo);
        if ($ret)
        {
            ManageUser::addUserToGroups($groups, $dn);
        }
        else
        {
            Logger::log(var_export($userInfo, true) . "\n: user cannot be modified");
            return new StatusReport($ret,ldap_error($connLdap->getConn()));
        }
        
        return new StatusReport($ret, ldap_error($connLdap->getConn()));
    }
    
    public static function changePassword($username, $password)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");

        $sr = ldap_search($connLdap->getConn(), Config::singleton()->ad_dc, "(sAMAccountName=" . $username . ")");
        $userInfo = ldap_get_entries($connLdap->getConn(), $sr);
        
        if (!isset($userInfo[0]))
        {
            Logger::log("User \"" . $username . "\" trying to change password attribute in AD not found in: \"" . Config::singleton()->ad_dc. "\".");
            return new StatusReport(false, "User \"" . $username . "\" trying to change password attribute in AD not found in: \"" . Config::singleton()->ad_dc. "\".");
        }
        
        $update["unicodePwd"] = \solideagle\plugins\ad\User::makeUnicodePsw($password);
        
        $ret = ldap_modify($connLdap->getConn(), $userInfo[0]["distinguishedname"][0], $update);
        if (!$ret)
            Logger::log(var_export($userInfo, true) . "\n: user password cannot be changed");

        return new StatusReport($ret, ldap_error($connLdap->getConn()));
    }
    
    public static function delUser($userName)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");
        $sr = ldap_search($connLdap->getConn(), Config::singleton()->ad_dc, "(sAMAccountName=" . $userName . ")");
        $userInfo = ldap_get_entries($connLdap->getConn(), $sr);
        if (!isset($userInfo[0]))
        {
            Logger::log("User \"" . $userName . "\" trying to delete in AD not found in: \"" . Config::singleton()->ad_dc. "\".");
            return new StatusReport(false, "Gebruiker \"" . $userName . "\" niet gevonden in: \"" . Config::singleton()->ad_dc. "\". Kan niet verwijderen.");
        }
        // delete user
        else 
        {
            $r = ldap_delete($connLdap->getConn(), $userInfo[0]['dn']);
        }
        return new StatusReport($r,ldap_error($connLdap->getConn()));
    }
    
    public static function setHomeFolder($username, $share)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");

        $sr = ldap_search($connLdap->getConn(), Config::singleton()->ad_dc, "(sAMAccountName=" . $username . ")");
        $userInfo = ldap_get_entries($connLdap->getConn(), $sr);
        
        if (!isset($userInfo[0]))
        {
            Logger::log("User \"" . $username . "\" trying to set homefolder attribute in AD not found in: \"" . Config::singleton()->ad_dc. "\".");
            return new StatusReport(false, "User \"" . $username . "\" trying to set homefolder attribute in AD not found in: \"" . Config::singleton()->ad_dc. "\".");
        }
        
        $update["homeDirectory"] = $share;
        
        $ret = ldap_modify($connLdap->getConn(), $userInfo[0]["distinguishedname"][0], $update);
        if (!$ret)
            Logger::log(var_export($userInfo, true) . "\n: user cannot be modified");

        return new StatusReport($ret, ldap_error($connLdap->getConn()));
    }
}

?>
