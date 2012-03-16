<?php

namespace AD;

require_once 'ConnectionLdap.php';
require_once 'data_access/Group.php';
require_once 'logging/Logger.php';
use DataAccess\Group;
use Logging\Logger;

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
             return array(false, "group or connection null");
        
        if ($childGroup == null)
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nChild group cannot be null.", PEAR_LOG_ERR);
            return false;
        }
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = $childGroup->getName();

        if ($arrParentsGroups == null)
        {
            ldap_add($connLdap->getConn(), "OU=" . $childGroup->getName() . ", " . AD_DC, $info);
        }
        else
        {
            $ouString = "";
            for($i = 0; $i < sizeof($arrParentsGroups); $i++)
            {
                $ouString .= "OU=" . $arrParentsGroups[$i]->getName() . ", ";
            }
          
            $r = ldap_add($connLdap->getConn(), "OU=" . $childGroup->getName() . ", " . $ouString . AD_DC, $info);
        }
        
        return array($r,ldap_error($connLdap->getConn()));
        
    }
    
    public static function updateOU($arrNewParentsGroups, $arrOldParentsGroups, $newChildGroup, $oldChildGroup)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($connLdap->getConn() == null)
            return false;
        
        if ($newChildGroup == null)
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nChild group cannot be null.", PEAR_LOG_ERR);
            return false;
        }
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = $newChildGroup->getName();
        
        // every group should have a parent, because root is gebruikers
        if ($arrNewParentsGroups == null)
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \n\"" . $newChildGroup->getName() . "\" group has no parents, every group should be child of root!", PEAR_LOG_ERR);
            return false;
        }
        
        $oldDn = "";
        for($i = 0; $i < sizeof($arrOldParentsGroups); $i++)
        {
            $oldDn .= "OU=" . $arrOldParentsGroups[$i]->getName() . ",";
        }
        $oldDn .= AD_DC;

        $sr = ldap_search($connLdap->getConn(), $oldDn, "(OU=" . $oldChildGroup->getName() . ")");
        $oldOuInfo = ldap_get_entries($connLdap->getConn(), $sr);
        
        if (!isset($oldOuInfo[0]))
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nOU \"" . $oldChildGroup->getName() . "\" trying to update in AD not found in: \"" . $oldDn . "\".",PEAR_LOG_ERR);
            return false;
        }
        
        $newParentDn = "";
        for($i = 0; $i < sizeof($arrNewParentsGroups); $i++)
        {
            $newParentDn .= "OU=" . $arrNewParentsGroups[$i]->getName() . ",";
        }
        $newParentDn .= AD_DC;
        $newDn = "OU=" . $newChildGroup->getName() . "," . $newParentDn;
        
        var_dump($oldOuInfo[0]['distinguishedname'][0]);
        var_dump($newChildGroup->getName());
        var_dump($newDn);
        var_dump($newParentDn);
        if ($oldOuInfo[0]['distinguishedname'][0] != $newDn)
        {
            $r = ldap_rename($connLdap->getConn(), $oldOuInfo[0]['distinguishedname'][0], "OU=" . $newChildGroup->getName(), $newParentDn, true);
            if ($r != 1)
            {
                Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nDN \"" . $oldOuInfo[0]['distinguishedname'][0] . "\" can't be removed or renamed to DN \"OU=" . $newChildGroup->getName() . "," . $newParentDn . "\".",PEAR_LOG_ERR);
            }
        }
    }
    
    public static function removeOU($arrParentsGroups, $childGroup)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($connLdap->getConn() == null)
            return false;
        
        if ($childGroup == null)
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nChild group cannot be null.", PEAR_LOG_ERR);
            return false;
        }
        
        // every group should have a parent, because root is gebruikers
        if ($arrParentsGroups == null)
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \n\"" . $childGroup->getName() . "\" group has no parents, every group should be child of root!", PEAR_LOG_ERR);
            return false;
        }
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = $childGroup->getName();
        
        $sr = ldap_search($connLdap->getConn(), "OU=" . AD_USERS_OU . ", " . AD_DC, "(OU=" . $childGroup->getName() . ")");
        $oldOuInfo = ldap_get_entries($connLdap->getConn(), $sr);

        if (!isset($oldOuInfo[0]))
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nOU \"" . $childGroup->getName() . "\" trying to update in AD not found in: \"OU=" . AD_USERS_OU . ", " . AD_DC . "\".",PEAR_LOG_ERR);
            return false;
        }
        
        $parentDn = "OU=" . $childGroup->getName() . ", ";
        for($i = 0; $i < sizeof($arrParentsGroups); $i++)
        {
            $parentDn .= "OU=" . $arrParentsGroups[$i]->getName() . ", ";
        }
        $parentDn .= AD_DC;
        $dn = "OU=" . $childGroup->getName() . ", " . $parentDn;

        // move user to other ou?
        if ($oldOuInfo[0]['distinguishedname'][0] != $dn)
        {
            ldap_rename($connLdap->getConn(), $oldOuInfo[0]['distinguishedname'][0], $childGroup->getName(), $parentDn, true);
        }
        
        
    }
}

?>
