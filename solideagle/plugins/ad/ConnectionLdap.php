<?php

namespace AD;

require_once 'data_access/Person.php';
require_once 'data_access/Group.php';
require_once 'config.php';
use DataAccess\Person;
use DataAccess\Group;


class ConnectionLDAP
{
    
    private static $instance;
    private $conn;

    public function __construct()
    {
        $this->conn = ldap_connect(AD_LDAPS_URL) or die("Could not connect to server");  
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
    
    public static function singleton()
    {
            if (!isset(self::$instance)) {
                    $className = __CLASS__;
                    self::$instance = new $className;
            }
            return self::$instance;
    }
    
    public function getConn()
    {
        return $this->conn;
    }

    public function setConn($conn)
    {
        $this->conn = $conn;
    }
    
    
    /**
     * Adds an OU to AD in the right place according to the parents
     * @param Group[] $arrParentsGroups     all the group parents of the child
     * @param Group $childGroup             the child group object (new OU to add)
     * @return boolean                      
     */
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
    }
    
}

?>
