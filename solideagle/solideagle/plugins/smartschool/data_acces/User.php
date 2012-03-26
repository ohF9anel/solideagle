<?php
namespace Smartschool;
require_once 'Api.php';

class User
{
	
	//saveUser properties
	private $internnumber;
	private $username;
	private $passwd1;
	private $passwd2;
	private $passwd3;
	private $name;
	private $surname;
	private $extranames;
	private $initials;
	private $sex;
	private $birthday;
	private $birthplace;
	private $birthcountry;
	private $address;
	private $postalcode;
	private $location;
	private $country;
	private $email;
	private $mobilephone;
	private $homephone;
	private $fax;
	private $prn;
	private $stamboeknummer;
	private $basisrol; //(verplicht op te geven: 'leerling', 'leerkracht' of 'andere')
	private $untis;
	
	//class properties
	private $classCodes = ""; //csv, see saveUserToClasses documentation from smartschool
	
	//setAccountStatus properties
	private $accountStatus = "actief";    /* 'actief', 'active' of 'enabled' om de account op actief te zetten
  											 'inactief', 'inactive' of 'disabled' om de account uit te schakelen
    										 'administrative' of 'administratief' om van de account een administratieve account te maken.*/
	
/*	public function __get($property) {
		if (property_exists($this, $property)) {
			return $this->$property;
		}
	}

	public function __set($property, $value) {
		if (property_exists($this, $property)) {
			$this->$property = $value;
		}
	}*/
	
	
	
	public function addClass($class) //make it comma separated values
	{
		if($this->classCodes === "")
			$this->classCodes.= $class;
		else
			$this->classCodes.= "," . $class;
	}
	
	public static function saveUser($user)
	{
		$returnvalue = 0; //the api returns 0 if it was succesfull
		
		
		assert('isset($user->internnumber) /* internnumber required!*/');
		assert('isset($user->username) /* username required!*/');
		assert('isset($user->passwd1) /* passwd1 required!*/');
		assert('isset($user->name) /* name required!*/');
		assert('isset($user->surname) /* surname required!*/');
		assert('isset($user->basisrol) /* basisrol required!*/');
			
		
		$api = Api::singleton();
		$returnvalue += $api->saveUser($user->internnumber,$user->username,$user->passwd1,$user->passwd2,$user->passwd3,$user->name,$user->surname,$user->extranames,$user->initials,$user->sex,$user->birthday,$user->birthplace,$user->birthcountry,$user->address,$user->postalcode,$user->location,$user->country,$user->email,$user->mobilephone,$user->homephone,$user->fax,$user->prn,$user->stamboeknummer,$user->basisrol,$user->untis);
		
		assert('$returnvalue == 0 /* return value not zero, something went wrong!*/');
		if($returnvalue != 0)
			return $returnvalue;
		
		$returnvalue += $api->saveUserToClasses($user->internnumber,$user->classCodes);
		
		assert('$returnvalue == 0 /* return value not zero, something went wrong!*/');
		if($returnvalue != 0)
			return $returnvalue;
		
		$returnvalue += $api->setAccountStatus($user->internnumber,$user->accountStatus);		
		
		assert('$returnvalue == 0 /* return value not zero, something went wrong!*/');
		
		return $returnvalue;
	}
	
	public static function removeUser($user)
	{
		$returnvalue = 0; //the api returns 0 if it was succesfull
		$api = Api::singleton();
		
		assert('isset($user->internnumber) /* internnumber required!*/');
		
		$api = Api::singleton();
		$returnvalue += $api->delUser($user->internnumber);		
		
		assert('$returnvalue == 0  /* return value not zero, something went wrong!*/');
		
		return $returnvalue;
	}
	



	public function setInternnumber($internnumber)
	{
	    $this->internnumber = $internnumber;
	}

	public function setUsername($username)
	{
	    $this->username = $username;
	}

	public function setPasswd1($passwd1)
	{
	    $this->passwd1 = $passwd1;
	}

	public function setPasswd2($passwd2)
	{
	    $this->passwd2 = $passwd2;
	}

	public function setPasswd3($passwd3)
	{
	    $this->passwd3 = $passwd3;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}

	public function setSurname($surname)
	{
	    $this->surname = $surname;
	}

	public function setExtranames($extranames)
	{
	    $this->extranames = $extranames;
	}

	public function setInitials($initials)
	{
	    $this->initials = $initials;
	}

	public function setSex($sex)
	{
	    $this->sex = $sex;
	}

	public function setBirthday($birthday)
	{
	    $this->birthday = $birthday;
	}

	public function setBirthplace($birthplace)
	{
	    $this->birthplace = $birthplace;
	}

	public function setBirthcountry($birthcountry)
	{
	    $this->birthcountry = $birthcountry;
	}

	public function setAddress($address)
	{
	    $this->address = $address;
	}

	public function setPostalcode($postalcode)
	{
	    $this->postalcode = $postalcode;
	}

	public function setLocation($location)
	{
	    $this->location = $location;
	}

	public function setCountry($country)
	{
	    $this->country = $country;
	}

	public function setEmail($email)
	{
	    $this->email = $email;
	}

	public function setMobilephone($mobilephone)
	{
	    $this->mobilephone = $mobilephone;
	}

	public function setHomephone($homephone)
	{
	    $this->homephone = $homephone;
	}

	public function setFax($fax)
	{
	    $this->fax = $fax;
	}

	public function setPrn($prn)
	{
	    $this->prn = $prn;
	}

	public function setStamboeknummer($stamboeknummer)
	{
	    $this->stamboeknummer = $stamboeknummer;
	}

	public function setBasisrol($basisrol)
	{
	    $this->basisrol = $basisrol;
	}

	public function setUntis($untis)
	{
	    $this->untis = $untis;
	}

	public function setAccountStatus($accountStatus)
	{
	    $this->accountStatus = $accountStatus;
	}
}
?>