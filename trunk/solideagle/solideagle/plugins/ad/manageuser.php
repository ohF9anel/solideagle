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
        
        // set description
        $userInfo['description'] = "Solid Eagle made me on " . date('YmdHis');
        
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
        
        $r = ldap_add($connLdap->getConn(), ConnectionLdap::ldap_escape($dn), $userInfo);
        if ($r)
        {
            ManageUser::addUserToGroup($groups[0], $dn);
        }
        else 
        {
            Logger::log("User: " . var_export($userInfo, true) . " cannot be added in: " . $dn);
        }
        
        return new StatusReport($r, ldap_error($connLdap->getConn()));
    }
    
    public static function addUserToGroup($group, $dn)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");
        
        $r = true;
        
        // add user to correct group
        if ($group != null)
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
        //$userInfo["useraccountcontrol"] = $userInfo['enabled'] ? "66048" : "66050";
        
        $group = $userInfo['groups'][0];
//        $groups = $userInfo['groups'];
        
        // get old userinfo
        $sr = ldap_search($connLdap->getConn(), Config::singleton()->ad_dc, "(sAMAccountName=" . $userInfo['sAMAccountName'] . ")");
        $oldUserInfo = ldap_get_entries($connLdap->getConn(), $sr);
        //var_dump($oldUserInfo);
        if (!isset($oldUserInfo[0]))
        {
            Logger::log("User \"" . $userInfo['uid'] . "\" trying to update in AD not found in: \"" . Config::singleton()->ad_dc. "\".");
            return false;
        }

//        
        $ac = $oldUserInfo[0]["useraccountcontrol"][0];

        $disable = ($ac |  2); // set all bits plus bit 1 (=dec2)
        $enable = ($ac & ~2); // set all bits minus bit 1 (=dec2)
        $userInfo["useraccountcontrol"] = $userInfo['enabled'] ? $enable : $disable;
        
        // rename cn later?
        $rename = ($oldUserInfo[0]['cn'] != $userInfo['cn']) ? true : false;
        
        // save cn
        $cn = $userInfo['cn'];
        
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

        $ret = ldap_modify($connLdap->getConn(), $oldUserInfo[0]['dn'], $userInfo);
        if (!$ret)
        {
            Logger::log(var_export($userInfo, true) . "\n: user cannot be modified");
            return new StatusReport($ret, ldap_error($connLdap->getConn()));
        }
        
        $parentDn = "OU=" . $group->getName() . ",";
        for($i = 0; $i < sizeof($arrParentsOUs); $i++)
        {
            $parentDn .= "OU=" . $arrParentsOUs[$i]->getName() . ",";
        }

        $parentDn .= Config::singleton()->ad_dc;
        //$dn = "CN=" . $userInfo['cn'] . "," . $parentDn;
        
        if ($rename)
        {
            $ret = ldap_rename($connLdap->getConn(), $oldUserInfo[0]['distinguishedname'][0], 'CN=' . $cn, $parentDn, true);
        
            if (!$ret)
            {
                Logger::log(var_export($userInfo, true) . "\n: user's cn cannot be renamed");
                return new StatusReport($ret, ldap_error($connLdap->getConn()));
            }
        }
        
        
        
//        if ($ret)
//        {
//            ManageUser::addUserToGroups($groups, $dn);
//        }
//        else
//        {
//            Logger::log(var_export($userInfo, true) . "\n: user cannot be modified");
//            return new StatusReport($ret,ldap_error($connLdap->getConn()));
//        }
//        
        return new StatusReport($ret, ldap_error($connLdap->getConn()));
    }
    
    public static function moveUser($userInfo, $newParentsOUs, $oldParentsOUs)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");

        // dn of new parent ous
        $newParentDn = "";
        for($i = 0; $i < sizeof($newParentsOUs); $i++)
        {
            $newParentDn .= "OU=" . $newParentsOUs[$i]->getName() . ",";
        }

        $newParentDn .= Config::singleton()->ad_dc;
        
        // dn of old parent ous
        $oldParentDn = "";
        for($i = 0; $i < sizeof($oldParentsOUs); $i++)
        {
            $oldParentDn .= "OU=" . $oldParentsOUs[$i]->getName() . ",";
        }
        $oldParentDn .= Config::singleton()->ad_dc;
        
        $dn = "CN=" . $userInfo['cn'] . "," . $oldParentDn;
        
        $ret = ldap_rename($connLdap->getConn(), $dn, 'CN=' . $userInfo['cn'], $newParentDn, true);
        
        if (!$ret)
        {
            Logger::log(var_export($userInfo, true) . "\n: user cannot be moved");
            return new StatusReport($ret, ldap_error($connLdap->getConn()));
        }
        
        // assign new groups
        $ret = self::giveUserNewGroups($userInfo, $newParentsOUs, $oldParentsOUs);
        if (!$ret)
        {
            Logger::log(var_export($userInfo, true) . "\n: " . ldap_error($connLdap->getConn()));
            return new StatusReport($ret, ldap_error($connLdap->getConn()));
        }
        
        return new StatusReport($ret, ldap_error($connLdap->getConn()));
    }
    
    public static function giveUserNewGroups($userInfo, $newParentsOUs, $oldParentsOUs)
    {
        $connLdap = ConnectionLDAP::singleton();
        if ($connLdap->getConn() == null)
            return new StatusReport(false, "Connection to AD cannot be made.");

        // remove previous group memberships
        $dngroup = "CN=" . $oldParentsOUs[0]->getName() . ",";
        $dngroup .= "OU=" . Config::singleton()->ad_groups_ou . ",";
        $dngroup .= Config::singleton()->ad_dc;

        $dnuser = "CN=" . $userInfo['cn'] . ",";
        for($i = 0; $i < sizeof($newParentsOUs); $i++)
            $dnuser .= "OU=" . $newParentsOUs[$i]->getName() . ",";
        $dnuser .= Config::singleton()->ad_dc;

        $group_info['member'] = $dnuser;
        $ret = ldap_mod_del($connLdap->getConn(), $dngroup, $group_info);
        if (!$ret)
        {
            Logger::log(var_export($userInfo, true) . "\n: user cannot be removed from security group");
            return new StatusReport($ret, ldap_error($connLdap->getConn()));
        }

        // asign new group
        $ret = self::addUserToGroup($newParentsOUs[0], $dnuser);
        if (!$ret)
        {
            Logger::log(var_export($userInfo, true) . "\n: user cannot be to security group");
            return new StatusReport($ret, ldap_error($connLdap->getConn()));
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
        
        $update["homeDirectory"] = $share . "\\" . $username . "$";
        $update["homeDrive"] = "T";
        
        $ret = ldap_modify($connLdap->getConn(), $userInfo[0]["distinguishedname"][0], $update);
        if (!$ret)
            Logger::log(var_export($userInfo, true) . "\n: user cannot be modified");

        return new StatusReport($ret, ldap_error($connLdap->getConn()));
    }
}

?>
