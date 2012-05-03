<?php

namespace solideagle\plugins\ad;

use solideagle\data_access\Person;
use solideagle\data_access\Group;

class User 
{
    
    //properties - same name as ldap_add key values
    private $cn;
    private $uid;
    private $sAMAccountName;
    private $unicodePwd;
    private $sn;
    private $givenname;
    private $displayName;
    private $userprincipalname;
    private $objectclass;
    private $streetaddress;
    private $postofficebox;
    private $postalcode;
    private $l;
    private $co;
    private $telephoneNumber;
    private $mobile;
    private $mail;
    private $info;
    private $homeDirectory;    
    
    // to be unset before adding
    private $groups = array();
    private $enabled = true;
    
    /**
     * returns all properties of an AD user
     * @return array User  
     */
    public function getUserInfo() 
    {
        return get_object_vars($this);
    }

    public function setCn($cn)
    {
        $this->cn = $cn;
    }

    public function setUid($uid)
    {
        $this->uid = $uid;
    }

    public function setSAMAccountName($sAMAccountName)
    {
        $this->sAMAccountName = $sAMAccountName;
    }

    public function setUnicodePwd($unicodePwd)
    {
        $this->unicodePwd = self::makeUnicodePsw($unicodePwd);
    }

    public function setSn($sn)
    {
        $this->sn = $sn;
    }

    public function setGivenname($givenname)
    {
        $this->givenname = $givenname;
    }
    
    public function setDisplayName($displayName)
    {
        $this->displayName = $displayName;
    }

    public function setUserprincipalname($userprincipalname)
    {
        $this->userprincipalname = $userprincipalname;
    }

    public function setObjectclass($objectclass)
    {
        $this->objectclass = $objectclass;
    }

    public function setStreetaddress($streetaddress)
    {
        $this->streetaddress = $streetaddress;
    }

    public function setPostofficebox($postofficebox)
    {
        $this->postofficebox = $postofficebox;
    }

    public function setPostalcode($postalcode)
    {
        $this->postalcode = $postalcode;
    }

    public function setL($l)
    {
        $this->l = $l;
    }

    public function setCo($co)
    {
        $this->co = $co;
    }

    public function setTelephoneNumber($telephoneNumber)
    {
        $this->telephoneNumber = $telephoneNumber;
    }

    public function setMobile($mobile)
    {
        $this->mobile = $mobile;
    }

    public function setMail($mail)
    {
        $this->mail = $mail;
    }

    public function setInfo($info)
    {
        $this->info = $info;
    }
    
    public function addMemberOfGroups($groups)
    {
        $this->groups[] = $groups;
    }
    
    public function getMemberOfGroups()
    {
        return $this->groups;
    }
    
    public function getEnabled()
    {
        return $this->enabled;
    }

    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }
    
    public function getCn()
    {
        return $this->cn;
    }
    
    public function setHomeDirectory($homeDirectory)
    {
        $this->homeDirectory = $homeDirectory;
    }
    
    public static function convertPersonToAdUser($person, $enabled = true)
    {
        $user = new User();
        
        $user->setCn($person->getFirstName() . ' ' . $person->getName() . ' (' . $person->getAccountUserName() . ')');
        $user->setUid($person->getAccountUserName());
        $user->setSAMAccountName($person->getAccountUserName());
        $user->setUnicodePwd($person->getAccountPassword());
        $user->setSn($person->getName());
        $user->setGivenname($person->getFirstName());
        $user->setUserprincipalname($person->getAccountUserName());
        $user->setDisplayName($person->getFirstName() . ' ' . $person->getName());
        $user->setStreetaddress($person->getStreet());
        $user->setPostofficebox($person->getHouseNumber());
        $user->setPostalcode($person->getPostCode());
        $user->setL($person->getCity());
        $user->setCo($person->getCountry());
        $user->setTelephoneNumber($person->getPhone());
        $user->setMobile($person->getMobile());
        $user->setMail($person->getEmail());
        $user->setInfo($person->getOtherInformation());

        $user->setEnabled($enabled);
        

        $user->addMemberOfGroups(Group::getGroupById($person->getGroupId()));
        
        return $user;
    }
    
    public static function makeUnicodePsw($password)
    {
        $newPassword = "\"" . $password . "\"";
        $len = strlen($newPassword);
        $newPassw = "";
        for($i=0;$i<$len;$i++)
        $newPassw .= "{$newPassword{$i}}\000";
        
        return $newPassw;
    }
    
    
    
    

}

?>
