<?php

namespace solideagle\scripts\imports;

use solideagle\data_access\Person;

use solideagle\utilities\csvparser;

use solideagle\data_access\Group;

use solideagle\data_access\Type;

class importstaff
{
	private $fileptr;
	
	const staffGroup = "staff"; 
	const teacherGroup = "leerkrachten"; 
	
	/**
	 * When going out of scope we will automatically close the file, see the destructor
	 * 
	 * @param Fileptr $fileptr
	 */
	public function __construct($fileptr)
	{
		$this->fileptr = $fileptr;
	}
	
	public function import()
	{
		

		$personparser = new csvparser($this->fileptr,";");
			
		$personparser->getFromField("name", "Naam");
		$personparser->getFromField("informatid", "Nr. Personeelslid");
		$personparser->getFromField("firstname", "Voornaam");
		$personparser->getFromField("gender", "Geslacht");
		$personparser->getFromField("street", "Domicilie-adres");
		$personparser->getFromField("postcode","Deelpostnr (domicilie)");
		$personparser->getFromField("country","Land (domicilie)");
		$personparser->getFromField("city","Deelgemeente (domicilie)");
		$personparser->getFromField("phone","Domicilie-telefoon");
		$personparser->getFromField("mobile","Domicilie-gsm");
		$personparser->getFromField("email","E-mail adres (privé)");
		$personparser->getFromField("type","Hoofdambt");
			
		if(count(($notfoundfields = $personparser->canParse())) > 0)
		{
			//some fields were not found in csv, abort
			echo "FOUT: Deze velden konden niet gevonden worden: <br />";
			foreach($notfoundfields as $fieldname)
			{
				echo $fieldname . "<br />";
			}
			return;
		}
			
		$arr = $personparser->parse();
		
		$retclass = new \stdClass();
		$retclass->new = array();
		$retclass->updated = array();
		 		
		foreach($arr as $personattr)
		{
			//is deze gebruiker al geimporteerd?
			if(Person::userExistsByInformatId($personattr->informatid))
			{
				if($this->hasChanged($personattr))
				{
					$retclass->updated[] = $personattr;
				}
			}else{
				$retclass->new[] = $personattr;
			}
		}
		
		return $retclass;
	}
	
	private function hasChanged($personattr)
	{
		//TODO
		return false;
	}
	
	public static function updateUsers($arr)
	{
		foreach($arr as $personattr)
		{
			self::updateUser($personattr);
		}
	}
	
	private function updateUser($personattr)
	{
		//TODO
	}
	
	public static function addUsers($arr)
	{
		if(Group::getGroupByName(self::staffGroup) === NULL )
		{
			echo "FOUT: De groep ". self::staffGroup ." bestaat niet!";
			return;
		}
			
		if(Group::getGroupByName(self::teacherGroup) === NULL)
		{
			echo "FOUT: De groep ".self::teacherGroup." bestaat niet!";
			return;
		}
		
		foreach($arr as $personattr)
		{
			self::newUser($personattr);
		}
	}
	
	private static function newUser($personattr)
	{
		$person= new Person();
		$person->setName($personattr->name);
		$person->setInformatId($personattr->informatid);
		$person->setFirstName($personattr->firstname);
		$person->setGender($personattr->gender);
		$person->setStreet($personattr->street);
		$person->setPostCode($personattr->postcode);
		$person->setCountry($personattr->country);
		$person->setCity($personattr->city);
		$person->setPhone($personattr->phone);
		$person->setMobile($personattr->mobile);
		$person->setEmail($personattr->email);
		
		if( strpos(strtolower($personattr->type), "leraar") === false)
		{
			$person->addType(new Type(Type::TYPE_STAFF));
			$person->setGroupId(Group::getGroupByName(self::staffGroup)->getId()); //we expect the group staff to exist...
		}else{
			$person->addType(new Type(Type::TYPE_LEERKRACHT));
			$person->setGroupId(Group::getGroupByName(self::teacherGroup)->getId()); //we expect the group leerkrachten to exist...
		}
		
		$person->setAccountUsername(Person::generateUsername($person));
		$person->setAccountPassword(Person::generatePassword());
		
		Person::addPerson($person);
	}
	
	public function __destruct()
	{
		fclose($this->fileptr);
	}
	
	
}