<?php

namespace solideagle\plugins\ad;


use solideagle\plugins\StatusReport;

use solideagle\data_access\Group;
use solideagle\logging\Logger;
use solideagle\Config;

class ManageOU
{
    /**
     * Adds an OU to AD in the right place according to the parents
     * @param Group[] $arrParentsGroups     all the group parents of the child
     * @param Group $childGroup             the child group object (new OU to add)
     * @return boolean                      
     */
    public static function addOU($arrParentsGroups, $childGroup)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($connLdap->getConn() == null)
             return new StatusReport(false,"Connectie met LDAP mislukt");
        
        if ($childGroup == null)
        {
            Logger::log("Child group cannot be null.");
            return new StatusReport(false,"Nieuwe group is NULL");
        }
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = $childGroup->getName();

        if ($arrParentsGroups == null)
        {
            ldap_add($connLdap->getConn(), "OU=" . ConnectionLdap::ldap_escape($childGroup->getName(), true) . ", " . Config::$ad_dc, $info);
        }
        else
        {
            $ouString = "";
            for($i = 0; $i < sizeof($arrParentsGroups); $i++)
            {
                $ouString .= "OU=" . $arrParentsGroups[$i]->getName() . ", ";
            }
 
            $r = ldap_add($connLdap->getConn(), "OU=" . ConnectionLdap::ldap_escape($childGroup->getName(),true) . ", " . $ouString . Config::$ad_dc, $info);
        }
        
        return new StatusReport($r,ldap_error($connLdap->getConn()));
    }
    
    public static function moveOU($oldparents,$newparents,$group)
    {
    	$connLdap = ConnectionLdap::singleton();
	 
    	$oldDn = "";
    	for($i = 0; $i < sizeof($oldparents); $i++)
    	{
    	$oldDn .= "OU=" . $oldparents[$i]->getName() . ",";
    	}
    	$oldDn .= Config::$ad_dc;
    	
    	$newParentDn = "";
    	for($i = 0; $i < sizeof($newparents); $i++)
    	{
    	$newParentDn .= "OU=" . $newparents[$i]->getName() . ",";
    	}
    	$newParentDn .= Config::$ad_dc;
    	 
    	$sr = ldap_search($connLdap->getConn(), $oldDn, "(OU=" . ConnectionLdap::escapeForLDAPSearch($group->getName()) . ")");
    	$oldOuInfo = ldap_get_entries($connLdap->getConn(), $sr);
    	 
    	$r = ldap_rename($connLdap->getConn(), $oldOuInfo[0]['distinguishedname'][0], "OU=" . ConnectionLdap::ldap_escape($group->getName(), true), $newParentDn, true);
    	 
    	echo ldap_error($connLdap->getConn());
    	
    	return new StatusReport($r,ldap_error($connLdap->getConn()));
    }
    
    public static function modifyOU($parents,$oldgroup,$newgroup)
    {
    	$connLdap = ConnectionLdap::singleton();
    	
    	$oldDn = "";
    	for($i = 0; $i < sizeof($parents); $i++)
    	{
    	$oldDn .= "OU=" . ConnectionLdap::ldap_escape($parents[$i]->getName(), true) . ",";
    	}
    	$oldDn .= Config::$ad_dc;
    	
    	$sr = ldap_search($connLdap->getConn(), $oldDn, "(OU=" . ConnectionLdap::escapeForLDAPSearch($oldgroup->getName()) . ")");
    	$oldOuInfo = ldap_get_entries($connLdap->getConn(), $sr);
    	
    	$r = ldap_rename($connLdap->getConn(), $oldOuInfo[0]['distinguishedname'][0], "OU=" . ConnectionLdap::ldap_escape($newgroup->getName(), true), NULL, true);
    	
    	return new StatusReport($r,ldap_error($connLdap->getConn()));
    }
    
    public static function removeOU($arrParentsGroups, $childGroup)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($connLdap->getConn() == null)
            return false;
        
        if ($childGroup == null)
        {
            Logger::log("Child group cannot be null.");
            return false;
        }
        
        // every group should have a parent, because root is gebruikers
        if ($arrParentsGroups == null)
        {
            Logger::log("\"" . $childGroup->getName() . "\" group has no parents, every group should be child of root (gebruikers)!");
            return false;
        }
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = ConnectionLDAP::escapeForLDAPSearch($childGroup->getName());
        
        $sr = ldap_search($connLdap->getConn(), Config::$ad_dc, "(OU=" .  $info["ou"] . ")");
        $oldOuInfo = ldap_get_entries($connLdap->getConn(), $sr);

        if (!isset($oldOuInfo[0]))
        {
            Logger::log("OU \"" . $childGroup->getName() . "\" trying to update in AD not found in: \"" . Config::$ad_dc. "\".");
            return false;
        }
        
       $parentDn = "OU=" . $childGroup->getName() . ", ";
        for($i = 0; $i < sizeof($arrParentsGroups); $i++)
        {
            $parentDn .= "OU=" . $arrParentsGroups[$i]->getName() . ", ";
        }
        $parentDn .= Config::$ad_dc;
        
        $ret = ldap_delete($connLdap->getConn(), $parentDn);

        return new StatusReport($ret ,ldap_error($connLdap->getConn()));
    }
}

?>
