<?php

namespace AD;

class User 
{
    
    //properties - same name as ldap_add key values
    private $cn;
    private $uid;
    private $sAMAccountName;
    //private $unicodePwd;
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
    private $homephone;
    private $mobile;
    private $mail;
    private $info;
    
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
        $this->unicodePwd = $unicodePwd;
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

    public function setHomephone($homephone)
    {
        $this->homephone = $homephone;
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
    
}

?>
