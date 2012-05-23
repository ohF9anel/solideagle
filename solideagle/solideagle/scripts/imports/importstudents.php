<?php

namespace solideagle\scripts\imports;

use solideagle\data_access\Person;

use solideagle\utilities\csvparser;

use solideagle\data_access\Group;

use solideagle\data_access\Type;

class importstudents
{
	private $fileptr;
	
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
		$personparser->getFromField("informatid", "Nr. Leerling");
		$personparser->getFromField("firstname", "Voornaam");
		$personparser->getFromField("klas", "Klascode");
		//$personparser->getFromField("gender", "Geslacht");
		
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
		$retclass->classes = array();
		 		
		foreach($arr as $personattr)
		{
			//create klas lijst
			$personattr->klas = preg_replace("/[^A-Za-z0-9]/", "", $personattr->klas);
			
			//ignore empty classes
			if(strlen($personattr->klas)== 0)
			{
				continue;
			}
			
			$class = new \stdClass();
			$class->name = $personattr->klas;
			$class->instellingsnummer = "";
			$class->administratievegroep = "";
			
			$retclass->classes[$personattr->klas] = $class;
			
			sort($retclass->classes);
			
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
		//$person->setGender($personattr->gender);

		$person->addType(new Type(Type::TYPE_LEERLING));
		
		$group = Group::getGroupByName($personattr->klas);
		
		if($group != NULL)
		{
			$person->setGroupId($group->getId()); //we expect the group to exist...
		}else{
			//group does not exist, add to root group
			echo "Opgelet! groep: " . $group->getName() . " bestaat niet\n";
			echo "Gebruiker onder root geplaatst\n";
			$person->setGroupId(1); 
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