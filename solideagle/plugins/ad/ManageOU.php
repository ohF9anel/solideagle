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
    public static function addOU($childGroup)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($childGroup == null || $connLdap->getConn() == null)
            return false;
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = $childGroup->getName();
        
        $arrParentsGroups = Group::getParents($childGroup);
        
        if ($arrParentsGroups == null)
        {
            $r = ldap_add($connLdap->getConn(), "OU=" . $childGroup->getName() . ", " . AD_DC, $info);
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
    }
    
    public static function updateOU($childGroup)
    {
        $connLdap = ConnectionLdap::singleton();
                
        if ($childGroup == null || $connLdap->getConn() == null)
            return false;
        
        $info['objectClass'] = "organizationalUnit";
        $info["ou"] = $childGroup->getName();
        
        $arrParentsGroups = Group::getParents($childGroup);
        
        var_dump($arrParentsGroups);
        
        // every group should have a parent, because root is gebruikers
        if ($arrParentsGroups == null)
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \n\"" . $childGroup->getName() . "\" group has no parents, every group should be child of root!", PEAR_LOG_ERR);
            return false;
        }
        
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

//        else
//        {
//            $ouString = "";
//            for($i = 0; $i < sizeof($arrParentsGroups); $i++)
//            {
//                $ouString .= "OU=" . $arrParentsGroups[$i]->getName() . ", ";
//            }
//            $r = ldap_add($connLdap->getConn(), "OU=" . $childGroup->getName() . ", " . $ouString . AD_DC, $info);
//        }
    
    }
}

?>
