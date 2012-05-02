<?php


namespace solideagle\data_access;

use solideagle\data_access\helpers\UnicodeHelper;

use solideagle\data_access\helpers\DateConverter;

function IsNullOrEmptyString($question){
	return (!isset($question) || trim($question)==='');
}

use solideagle\data_access\database\DatabaseCommand;
use solideagle\data_access\validation\Validator;
use solideagle\data_access\validation\ValidationError;
use solideagle\logging\Logger;

class Person
{
	// variables
	private $id;
	private $accountUsername;
	private $accountPassword;
	private $accountActiveUntill;
	private $accountActiveFrom;
	private $uniqueIdentifier;
	private $informatId;
	private $firstName;
	private $name;
	private $gender;
	private $birthDate;
	private $birthPlace;
	private $nationality;
	private $street;
	private $houseNumber;
	private $postCode;
	private $city;
	private $country;
	private $email;
	private $phone;
	private $phone2;
	private $mobile;
	private $madeOn = "";
	private $otherInformation;
	private $deleted = 0;
	private $studentPreviousSchool;
	private $studentStamnr;
	private $parentOccupation;
        private $pictureUrl;
	private $groupId;

	private $types = array();

	private $valErrors = array();
	
	//not stored in db
	private $year;
	
	//readonly
	private $hasAdAccount;
	private $hasSSAccount;
	private $hasGaAccount;

	public function __construct()
	{
			
		//$this->
			
	}

	// getters & setters

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getAccountUsername()
	{
		return $this->accountUsername;
	}

	public function setAccountUsername($accountUsername)
	{
		$this->accountUsername = $accountUsername;
	}

	public function getAccountPassword()
	{
		return $this->accountPassword;
	}

	public function setAccountPassword($accountPassword)
	{
		$this->accountPassword = $accountPassword;
	}

	public function getAccountActiveUntill()
	{
		return $this->accountActiveUntill;
	}

	public function setAccountActiveUntill($accountActiveUntill)
	{
		$this->accountActiveUntill = $accountActiveUntill;
	}

	public function getAccountActiveFrom()
	{
		return $this->accountActiveFrom;
	}

	public function setAccountActiveFrom($accountActiveFrom)
	{
		$this->accountActiveFrom = $accountActiveFrom;
	}



	public function getFirstName()
	{
		return $this->firstName;
	}

	public function setFirstName($firstName)
	{
		$this->firstName = $firstName;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	public function getGender()
	{
		return $this->gender;
	}

	public function setGender($gender)
	{
		$this->gender = $gender;
	}

	public function getBirthDate()
	{
		return $this->birthDate;
	}

	public function setBirthDate($birthDate)
	{
		$this->birthDate = $birthDate;
	}

	public function getBirthPlace()
	{
		return $this->birthPlace;
	}

	public function setBirthPlace($birthPlace)
	{
		$this->birthPlace = $birthPlace;
	}

	public function getNationality()
	{
		return $this->nationality;
	}

	public function setNationality($nationality)
	{
		$this->nationality = $nationality;
	}

	public function getStreet()
	{
		return $this->street;
	}

	public function setStreet($street)
	{
		$this->street = $street;
	}

	public function getHouseNumber()
	{
		return $this->houseNumber;
	}

	public function setHouseNumber($houseNumber)
	{
		$this->houseNumber = $houseNumber;
	}

	public function getPostCode()
	{
		return $this->postCode;
	}

	public function setPostCode($postCode)
	{
		$this->postCode = $postCode;
	}

	public function getCity()
	{
		return $this->city;
	}

	public function setCity($city)
	{
		$this->city = $city;
	}

	public function getCountry()
	{
		return $this->country;
	}

	public function setCountry($country)
	{
		$this->country = $country;
	}

	public function getEmail()
	{
		return $this->email;
	}

	public function setEmail($email)
	{
		$this->email = $email;
	}

	public function getPhone()
	{
		return $this->phone;
	}

	public function setPhone($phone)
	{
		$this->phone = $phone;
	}

	public function getPhone2()
	{
		return $this->phone2;
	}

	public function setPhone2($phone2)
	{
		$this->phone2 = $phone2;
	}

	public function getMobile()
	{
		return $this->mobile;
	}

	public function setMobile($mobile)
	{
		$this->mobile = $mobile;
	}

	public function getMadeOn()
	{
		return $this->madeOn;
	}

	public function setMadeOn($madeOn)
	{
		$this->madeOn = $madeOn;
	}

	public function getOtherInformation()
	{
		return $this->otherInformation;
	}

	public function setOtherInformation($otherInformation)
	{
		$this->otherInformation = $otherInformation;
	}

	public function getDeleted()
	{
		return $this->deleted;
	}

	public function setDeleted($deleted)
	{
		$this->deleted = $deleted;
	}

	public function getStudentPreviousSchool()
	{
		return $this->studentPreviousSchool;
	}

	public function setStudentPreviousSchool($studentPreviousSchool)
	{
		$this->studentPreviousSchool = $studentPreviousSchool;
	}

	public function getStudentStamnr()
	{
		return $this->studentStamnr;
	}

	public function setStudentStamnr($studentStamnr)
	{
		$this->studentStamnr = $studentStamnr;
	}

	public function getParentOccupation()
	{
		return $this->parentOccupation;
	}

	public function setParentOccupation($parentOccupation)
	{
		$this->parentOccupation = $parentOccupation;
	}
        
        public function getPictureUrl()
        {
            return $this->pictureUrl;
        }

        public function setPictureUrl($pictureUrl)
        {
            $this->pictureUrl = $pictureUrl;
        }
   
	public function getGroupId()
	{
		return $this->groupId;
	}

	public function setGroupId($groupId)
	{
		$this->groupId = $groupId;
	}

	public function addType($type)
	{
		$this->types[] = $type;
	}
	
	public function resetTypes()
	{
		$this->types = array();
	}

	public function getTypes()
	{
		return $this->types;
	}

	/*
	 * tries to generate a username
	*/
	public static function generateUsername($person,$isStudent=false)
	{
			
		$counter = "";
			
		$saneName = iconv("UTF-8", "ASCII//TRANSLIT",$person->getName());
		$saneFirstName = iconv("UTF-8", "ASCII//TRANSLIT",$person->getFirstName());
			
		$username =  $saneName . substr($saneFirstName,0,1);
			
		//strip all non letters
		$username = preg_replace("/[^A-Za-z]/", "", $username);
			
		$username = strtolower($username);// mb_strtolower($username, 'UTF-8');

		if($isStudent)
		{
			if(isset($person->year))
			{
				$username .= $person->year;
			}else{
				$username .= date("y");
			}
		}

		$sql = "select account_username from person where account_username = :accusername";
			
		$cmd = new DatabaseCommand();
			
		$cmd->BeginTransaction(); //should lock the table, even though we are only reading
			
		for(;;)
		{
			//echo "trying: " . $username . $counter . "\n";

			$cmd->newQuery($sql);

			$cmd->addParam(":accusername", $username . $counter);

			if($cmd->executeReader()->read())
			{
				$counter += 1;
			}else{
				break;
			}
		}
			
		$cmd->CommitTransaction();
			
		return $username . $counter;
	}

	public static function generatePassword($length = 8)
	{
		$passchars = 'aeuybdghjmnpqrstvzBDGHJLMNPQRSTVWXZAEUY23456789';

		$password = '';
			
		for ($i = 0; $i < $length; $i++) {
			$password .= $passchars[(rand() % strlen($passchars))];
		}
		return $password;
		//return "P@ssw0rd"; //cool and 1337 password that is as good as any other password!
	}

	/**
	 *
	 * @param Person $person
	 * @return int id
	 */
	public static function addPerson($person)
	{
		$person->setMadeOn(DateConverter::timestampDateToDb(time()));
			
		$err = Person::validatePerson($person);
		if (!empty($err))
		{
			Logger::log("Person not validated before saving! Validation errors:\n" . var_export($err,true) . "\nPerson object dump:\n" . var_export($person,true) . "\n",PEAR_LOG_ERR);
			return false;
		}

		$sql = "INSERT INTO  `person`
		(`id`,
		`account_username`,
		`account_password`,
		`uniqueIdentifier`,
		`informatId`,
		`account_active_untill`,
		`account_active_from`,
		`first_name`,
		`name`,
		`gender`,
		`birth_date`,
		`birth_place`,
		`nationality`,
		`street`,
		`house_number`,
		`post_code`,
		`city`,
		`country`,
		`email`,
		`phone`,
		`phone2`,
		`mobile`,
		`made_on`,
		`other_information`,
		`deleted`,
		`student_previous_school`,
		`student_stamnr`,
		`parent_occupation`,
                `picture_url`,
		`group_id`)
		VALUES
		(
		:id,
		:account_username,
		:account_password,
		:uniqueIdentifier,
		:informatId,
		:account_active_untill,
		:account_active_from,
		:first_name,
		:name,
		:gender,
		:birth_date,
		:birth_place,
		:nationality,
		:street,
		:house_number,
		:post_code,
		:city,
		:country,
		:email,
		:phone,
		:phone2,
		:mobile,
		:made_on,
		:other_information,
		:deleted,
		:student_previous_school,
		:student_stamnr,
		:parent_occupation,
                :picture_url,
		:group_id
		);
		";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":id", $person->getId());
		$cmd->addParam(":account_username", $person->getAccountUsername());
		$cmd->addParam(":account_password", $person->getAccountPassword());
		$cmd->addParam(":uniqueIdentifier",$person->getUniqueIdentifier());
		$cmd->addParam(":informatId",$person->getInformatId());
		$cmd->addParam(":account_active_untill", $person->getAccountActiveUntill());
		$cmd->addParam(":account_active_from", $person->getAccountActiveFrom());
		$cmd->addParam(":first_name", $person->getFirstName());
		$cmd->addParam(":name", $person->getName());
		$cmd->addParam(":gender", $person->getGender());
		$cmd->addParam(":birth_date", $person->getBirthDate());
		$cmd->addParam(":birth_place", $person->getBirthPlace());
		$cmd->addParam(":nationality", $person->getNationality());
		$cmd->addParam(":street", $person->getStreet());
		$cmd->addParam(":house_number", $person->getHouseNumber());
		$cmd->addParam(":post_code", $person->getPostCode());
		$cmd->addParam(":city", $person->getCity());
		$cmd->addParam(":country", $person->getCountry());
		$cmd->addParam(":email", $person->getEmail());
		$cmd->addParam(":phone", $person->getPhone());
		$cmd->addParam(":phone2", $person->getPhone2());
		$cmd->addParam(":mobile", $person->getMobile());
		$cmd->addParam(":made_on", $person->getMadeOn());
		$cmd->addParam(":other_information", $person->getOtherInformation());
		$cmd->addParam(":deleted", $person->getDeleted());
		$cmd->addParam(":student_previous_school", $person->getStudentPreviousSchool());
		$cmd->addParam(":student_stamnr", $person->getStudentStamNr());
		$cmd->addParam(":parent_occupation", $person->getParentOccupation());
                $cmd->addParam(":picture_url", $person->getPictureUrl());
		$cmd->addParam(":group_id", $person->getGroupId());

		$cmd->BeginTransaction();

		$cmd->execute();

		$cmd->newQuery("SELECT LAST_INSERT_ID();");

		$personId =  $cmd->executeScalar();

		// add person to correct type(s)

		$sql = "INSERT INTO  `type_person`
		(
		`type_id`,
		`person_id`
		)
		VALUES
		(
		:type_id,
		:person_id
		);";

		foreach($person->getTypes() as $type) {
			$cmd->newQuery($sql);
			$cmd->addParam(":type_id", $type->getId());
			$cmd->addParam(":person_id", $personId);

			$cmd->execute();
		}

		$cmd->CommitTransaction();

		return $personId;
	}

	/**
	 * Update user information, does not move user to group
	 * @param Person $person
	 */
	public static function updatePerson($person)
	{
		$err = Person::validatePerson($person);
		if (!empty($err))
		{
			Logger::log("Person not validated before saving! Validation errors:\n" . var_export($err,true) . "\nPerson object dump:\n" . var_export($person,true) . "\n",PEAR_LOG_ERR);
			return false;
		}

		$cmd = new DatabaseCommand();

		$cmd->BeginTransaction();

		// update new person's data

		$sql = "UPDATE  `person` SET
		`first_name` = :first_name,
		`name` = :name,
		`deleted` = :deleted,
		`account_password` = :account_password,
		`account_active_untill` = :account_active_untill,
		`account_active_from` = :account_active_from,
		`uniqueIdentifier` = :uniqueIdentifier,
		`informatId` = :informatId,
		`gender` = :gender,
		`birth_date` = :birth_date,
		`birth_place` = :birth_place,
		`nationality` = :nationality,
		`street` = :street,
		`house_number` = :house_number,
		`post_code` = :post_code,
		`city` = :city,
		`country` = :country,
		`email` = :email,
		`phone` = :phone,
		`phone2` = :phone2,
		`mobile` = :mobile,
		`other_information` = :other_information,
		`student_previous_school` = :student_previous_school,
		`student_stamnr` = :student_stamnr,
		`parent_occupation` = :parent_occupation,
                `picture_url` = :picture_url,
                `group_id` = :group_id
		WHERE id = :id;";

		$cmd->newQuery($sql);
		$cmd->addParam(":id", $person->getId());
		//$cmd->addParam(":account_username", $person->getAccountUsername());
		$cmd->addParam(":account_password", $person->getAccountPassword());
		$cmd->addParam(":uniqueIdentifier", $person->getUniqueIdentifier());
		$cmd->addParam(":informatId",$person->getInformatId());
		$cmd->addParam(":account_active_untill", $person->getAccountActiveUntill());
		$cmd->addParam(":account_active_from", $person->getAccountActiveFrom());
		$cmd->addParam(":first_name", $person->getFirstName());
		$cmd->addParam(":name", $person->getName());
		$cmd->addParam(":gender", $person->getGender());
		$cmd->addParam(":birth_date", $person->getBirthDate());
		$cmd->addParam(":birth_place", $person->getBirthPlace());
		$cmd->addParam(":nationality", $person->getNationality());
		$cmd->addParam(":street", $person->getStreet());
		$cmd->addParam(":house_number", $person->getHouseNumber());
		$cmd->addParam(":post_code", $person->getPostCode());
		$cmd->addParam(":city", $person->getCity());
		$cmd->addParam(":country", $person->getCountry());
		$cmd->addParam(":email", $person->getEmail());
		$cmd->addParam(":phone", $person->getPhone());
		$cmd->addParam(":phone2", $person->getPhone2());
		$cmd->addParam(":mobile", $person->getMobile());
		$cmd->addParam(":other_information", $person->getOtherInformation());
		$cmd->addParam(":deleted", $person->getDeleted());
		$cmd->addParam(":student_previous_school", $person->getStudentPreviousSchool());
		$cmd->addParam(":student_stamnr", $person->getStudentStamNr());
		$cmd->addParam(":parent_occupation", $person->getParentOccupation());
                $cmd->addParam(":picture_url", $person->getPictureUrl());
                $cmd->addParam(":group_id", $person->getGroupId());
		$cmd->execute();

		// updates person's type(s)

		$sql = "DELETE FROM  `type_person`
		WHERE `person_id` = :personId;";

		$cmd->newQuery($sql);
		$cmd->addParam(":personId", $person->getId());

		$cmd->execute();

		$sql = "INSERT INTO  `type_person`
		(
		`type_id`,
		`person_id`
		)
		VALUES
		(
		:type_id,
		:person_id
		);";

		foreach($person->getTypes() as $type) {
			$cmd->newQuery($sql);
			$cmd->addParam(":type_id", $type->getId());
			$cmd->addParam(":person_id", $person->getId());

			$cmd->execute();
		}

		$cmd->CommitTransaction();
	}

	public static function delPersonById($personId)
	{
		$sql = "DELETE FROM  `type_person`
		WHERE `person_id` = :personId;";

		$cmd = new DatabaseCommand($sql);


		$cmd->addParam(":personId", $personId);

		$cmd->execute();

		$sql = "DELETE FROM  `person`
		WHERE `id` = :id;";

			
		$cmd->newQuery($sql);
		$cmd->addParam(":id", $personId);

		$cmd->execute();
	}
	
	public static function createPersonByDbRow($retObj)
	{
		
		if($retObj === false)
			return NULL;
		
		$person = new Person();
		
		$person->setId($retObj->id);
		$person->setAccountUsername($retObj->account_username);
		$person->setAccountPassword($retObj->account_password);
		$person->setUniqueIdentifier($retObj->uniqueIdentifier);
		$person->setInformatId($retObj->informatId);
		$person->setAccountActiveUntill($retObj->account_active_untill);
		$person->setAccountActiveFrom($retObj->account_active_from);
		$person->setFirstName($retObj->first_name);
		$person->setName($retObj->name);
		$person->setGender($retObj->gender);
		$person->setBirthDate($retObj->birth_date);
		$person->setBirthPlace($retObj->birth_place);
		$person->setNationality($retObj->nationality);
		$person->setStreet($retObj->street);
		$person->setHouseNumber($retObj->house_number);
		$person->setPostCode($retObj->post_code);
		$person->setCity($retObj->city);
		$person->setCountry($retObj->country);
		$person->setEmail($retObj->email);
		$person->setPhone($retObj->phone);
		$person->setPhone2($retObj->phone2);
		$person->setMobile($retObj->mobile);
		$person->setMadeOn($retObj->made_on);
		$person->setOtherInformation($retObj->other_information);
		$person->setDeleted($retObj->deleted);
		$person->setStudentPreviousSchool($retObj->student_previous_school);
		$person->setStudentStamnr($retObj->student_stamnr);
		$person->setParentOccupation($retObj->parent_occupation);
                $person->setPictureUrl($retObj->picture_url);
		$person->setGroupId($retObj->group_id);
		$person->hasAdAccount = $retObj->platformad;
		$person->hasGaccount = $retObj->platformga;
		$person->hasSSAccount = $retObj->platformss;
		
		foreach(Type::getTypesByPersonid($person->getId()) as $ptype)
		{
			$person->addType($ptype);
		}
		
		return $person;
	}
	
	public static function getPersonsByIds($ids)
	{
		$params = "";
		for($i=0;$i<count($ids);$i++)
		{
			$params.= ",:param" . $i;
		}
		
		//cut off first semicol
		$params = substr($params, 1);
		
		$sql = "SELECT * FROM  `allPersons`
		WHERE `id` IN (" .$params. ")";
		
		$cmd = new DatabaseCommand($sql);
		for($i=0;$i<count($ids);$i++)
		{
			$cmd->addParam(":param".$i, $ids[$i]);
		}
		
		$retarr = array();
		
		$cmd->executeReader()->readAll(function($row) use (&$retarr){
			$retarr[] = Person::createPersonByDbRow($row);
		});
		
		return $retarr;
	}

	public static function getPersonById($id)
	{
		$sql = "SELECT * FROM  `allPersons`
		WHERE `id` = :id;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":id", $id);

		$reader = $cmd->executeReader();

		$retObj = $reader->read();
		
		$person = self::createPersonByDbRow($retObj);

		return $person;
	}

	/**
	 * Returns user id and username if valid user
	 * @param string $username
	 * @param string $password
	 * @param string $typeName
	 * @return var $personIdName    id and username
	 */
	public static function checkValidPersonByCredentials($username, $password, $typeName)
	{
		$sql = "SELECT
		`person`.`id`, `person`.`account_username`
		FROM  `person`,
		`type_person`,
		`type`
		WHERE `person`.`account_username` = :accountUsername
		AND `person`.`account_password` = :accountPassword
		AND `person`.`id` = `type_person`.`person_id`
		AND `type`.`id` = `type_person`.`type_id`
		AND `type`.`type_name` = :typeName
		;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":accountUsername", $username);
		$cmd->addParam(":accountPassword", $password);
		$cmd->addParam(":typeName", $typeName);

		$reader = $cmd->executeReader();

		$personIdName = $reader->read();

		return $personIdName;
	}

	
	//TODO check if uniqueident is unqiue, and username does not yet exist
	/**
	 * 
	 * @param Person $person
	 */
	public static function validatePerson($person)
	{
		$validationErrors = array();
			
		// account username
		$valErrors = Validator::validateString($person->getAccountUsername(), 1, 45, false);
		foreach ($valErrors as $valError)
		{
			switch($valError) {
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Gebruikersnaam: mag niet langer zijn dan 45 karakters."; break;
				case ValidationError::STRING_TOO_SHORT:
					$validationErrors[] = "Gebruikersnaam: te kort."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Gebruikersnaam: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Gebruikersnaam: fout."; break;
			}
		}

		// account password
		$valErrors = Validator::validateString($person->getAccountPassword(), 1, 64, true);
		foreach ($valErrors as $valError)
		{
			switch($valError) {
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Wachtwoord: mag niet langer zijn dan 45 karakters.";
					break;
				case ValidationError::STRING_TOO_SHORT:
					$validationErrors[] = "Wachtwoord: te kort.";
					break;
				default:
					$validationErrors[] = "Wachtwoord: fout."; break;
			}
		}
		if(!IsNullOrEmptyString($person->getAccountActiveUntill()))
		{
			// account active untill
			$valErrors = Validator::validateDate($person->getAccountActiveUntill(), true);
			foreach ($valErrors as $valError)
			{
				switch($valError) {
					case ValidationError::DATE_BAD_SYNTAX;
					$validationErrors[] = "Account actief tot: tijdstip moet ingegeven worden als YYYYMMDD.";
					break;
					case ValidationError::DATE_DOES_NOT_EXIST;
					$validationErrors[] = "Account actief tot: deze datum bestaat niet.";
					break;

					default:
						$validationErrors[] = "Account actief tot: fout."; break;
				}
			}
		}

		if(!IsNullOrEmptyString($person->getAccountActiveFrom()))
		{
			// account active from
			$valErrors = Validator::validateDate($person->getAccountActiveFrom(), true);
			foreach ($valErrors as $valError)
			{
				switch($valError) {
					case ValidationError::DATE_BAD_SYNTAX;
					$validationErrors[] = "Account actief vanaf: Tijdstip moet ingegeven worden als YYYYMMDD.";
					break;
					case ValidationError::DATE_DOES_NOT_EXIST;
					$validationErrors[] = "Account actief vanaf: Deze datum bestaat niet.";
					break;

					default:
						$validationErrors[] = "Account actief vanaf: fout."; break;
				}
			}
		}

		// first name
		$valErrors = Validator::validateString($person->getFirstName(), 1, 45);
		foreach ($valErrors as $valError)
		{
			switch($valError) {
				case ValidationError::STRING_TOO_SHORT:
					$validationErrors[] = "Voornaam: geef een voornaam in."; break;
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Voornaam: mag niet langer zijn dan 45 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Voornaam: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Voornaam: fout."; break;
			}
		}

		// name
		$valErrors = Validator::validateString($person->getName(), 1, 45);
		foreach ($valErrors as $valError)
		{
			switch($valError) {
				case ValidationError::STRING_TOO_SHORT:
					$validationErrors[] = "Familienaam: geef een familienaam in."; break;
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Familienaam: mag niet langer zijn dan 45 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Familienaam: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Familienaam: fout."; break;
			}
		}

		if(!IsNullOrEmptyString($person->getBirthDate()))
		{

			// birth date
			$valErrors = Validator::validateDateOccurrence($person->getBirthDate(), true);
			foreach ($valErrors as $valError)
			{
				switch($valError) {
					case ValidationError::DATE_BAD_SYNTAX;
					$validationErrors[] = "Geboortedatum: datum moet ingegeven worden als YYYYMMDD."; break;
					case ValidationError::DATE_DOES_NOT_EXIST;
					$validationErrors[] = "Geboortedatum: deze datum bestaat niet."; break;
					case ValidationError::DATE_IS_FUTURE;
					$validationErrors[] = "Geboortedatum: moet in het verleden zijn."; break;
					default:
						$validationErrors[] = "Geboortedatum: fout."; break;
				}
			}
		}

		// birth place
		$valErrors = Validator::validateString($person->getBirthPlace(), 0, 45);
		foreach ($valErrors as $valError)
		{
			switch($valError) {
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Geboorteplaats: mag niet langer zijn dan 45 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Geboorteplaats: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Geboorteplaats: fout."; break;
			}
		}

		// nationality
		$valErrors = Validator::validateString($person->getNationality(), 0, 45);
		foreach ($valErrors as $valError)
		{
			switch($valError) {
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Nationaliteit: mag niet langer zijn dan 45 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Nationaliteit: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Nationaliteit: fout."; break;
			}
		}

		// street
		$valErrors = Validator::validateString($person->getStreet(), 0, 120);
		foreach ($valErrors as $valError)
		{
			switch ($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Straatnaam: mag niet langer zijn dan 120 karakters.";
					break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Straatnaam: mag geen speciale tekens bevatten.";
					break;
				default:
					$validationErrors[] = "Straatnaam: fout.";
					break;
			}
		}

		// house_number
		$valErrors = Validator::validateString($person->getHouseNumber(), 0, 40);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Huisnummer: mag niet langer zijn dan 40 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Huisnummer: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Huisnummer: fout."; break;
			}
		}

		// post code
		$valErrors = Validator::validateString($person->getPostCode(), 0, 4);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Postcode: moet een nummer zijn van 4 cijfers.";
					break;
				default:
					$validationErrors[] = "Postcode: fout."; break;
			}
		}

		// city
		$valErrors = Validator::validateString($person->getCity(), 0, 40);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Gemeente: mag niet langer zijn dan 40 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Gemeente: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Gemeente: fout."; break;
			}
		}

		// country
		$valErrors = Validator::validateString($person->getCountry(), 0, 45);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Land: mag niet langer zijn dan 45 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Land: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Land: fout."; break;
			}
		}

		// email
		$valErrors = Validator::validateEmailAddress($person->getEmail());
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::EMAIL_ADDRESS_INVALID:
					$validationErrors[] = "E-mailadres: geef een geldig e-mailadres in."; break;
				default:
					$validationErrors[] = "E-mailadres: fout."; break;
			}
		}

		// phone
		$valErrors = Validator::validateString($person->getPhone(), 0, 30);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Telefoonnummer: mag niet langer zijn dan 30 karakters."; break;
				default:
					$validationErrors[] = "Telefoonnummer: fout."; break;
			}
		}

		// phone2
		$valErrors = Validator::validateString($person->getPhone2(), 0, 30);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Telefoonnummer 2: mag niet langer zijn dan 30 karakters."; break;
				default:
					$validationErrors[] = "Telefoonnummer 2: fout."; break;
			}
		}

		// mobile
		$valErrors = Validator::validateString($person->getMobile(), 0, 30);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "GSM-nummer: mag niet langer zijn dan 30 karakters."; break;
				default:
					$validationErrors[] = "GSM-nummer: fout."; break;
			}
		}

		// made on
		if(!IsNullOrEmptyString($person->getMadeOn()))
		{
			$valErrors = Validator::validateDateTimeOccurrence($person->getMadeOn(), true);
			foreach ($valErrors as $valError)
			{
				switch($valError) {
					case ValidationError::DATE_BAD_SYNTAX:
						$validationErrors[] = "Gemaakt op: datum moet ingegeven worden als YYYYMMDD."; break;
					case ValidationError::DATE_DOES_NOT_EXIST:
						$validationErrors[] = "Gemaakt op: deze datum bestaat niet."; break;
					case ValidationError::TIME_DOES_NOT_EXIST:
						$validationErrors[] = "Gemaakt op: dit tijdstip bestaat niet."; break;
					case ValidationError::DATE_IS_FUTURE:
						$validationErrors[] = "Gemaakt op: moet in het verleden zijn."; break;
					default:
						$validationErrors[] = "Gemaakt op: fout."; break;
				}
			}
		}
		// previous school
		$valErrors = Validator::validateString($person->getStudentPreviousSchool(), 0, 50);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Vorige school student: mag niet langer zijn dan 50 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Vorige school: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Vorige school: fout."; break;
			}
		}

		// student stamnr
		$valErrors = Validator::validateString($person->getStudentStamnr(), 0, 11);
		foreach ($valErrors as $valError)
		{
			switch($valError)
			{
				case ValidationError::STRING_TOO_LONG:
					$validationErrors[] = "Stamnummer student: mag niet langer zijn dan 11 karakters."; break;
				case ValidationError::STRING_HAS_SPECIAL_CHARS:
					$validationErrors[] = "Stamnummer student: mag geen speciale tekens bevatten."; break;
				default:
					$validationErrors[] = "Stamnummer student: fout."; break;
			}
		}
		
		if($person->getGroupId()===NULL)
		{
			$validationErrors[] = "Gebruiker heeft geen groep!";
		}

		return $validationErrors;
	}

	public static function isValidPerson($person)
	{
		$errors = Person::validatePerson($person);

		if (sizeof($errors) > 0)
		{
			return false;
		}
			

		return true;
	}

	/**
	 *
	 * Pass -1 to get all users
	 * Will only partially fill the user object!
	 * @param int $groupid
	 */
	public static function getUsersForDisplayByGroup($groupid = -1)
	{
			
		if($groupid !== -1)
		{
			$sql = "SELECT
			`person`.`id`,
			`person`.`account_username`,
				
			`person`.`first_name`,
			`person`.`name`,
			`person`.`made_on`
			FROM  `person`
			WHERE
			`person`.`group_id` = :groupid
			AND
			`person`.`deleted` = 0
			ORDER BY `person`.`made_on` desc";
		}else{
			$sql = "SELECT
			`person`.`id`,
			`person`.`account_username`,
				
			`person`.`first_name`,
			`person`.`name`,
			`person`.`made_on`
			FROM  `person` WHERE `person`.`deleted` = 0 ORDER BY `person`.`made_on` desc";
		}
			
		$cmd = new DatabaseCommand($sql);
			
		$cmd->addParam(":groupid", $groupid);
			
		$retarr = array();
			
		$cmd->executeReader()->readAll(function($row) use (&$retarr){

			$tempperson = new Person();
			$tempperson->setId($row->id);
			$tempperson->setName($row->name);
			$tempperson->setFirstName($row->first_name);
			$tempperson->setAccountUsername($row->account_username);
				
			$tempperson->setMadeOn($row->made_on);

			$retarr[] = $tempperson;

		});
			
			
		return $retarr;
	}

	public function isTypeOf($typeToCheck)
	{
		foreach($this->types as $type)
		{
			if($type->getId() == $typeToCheck)
			{
				return true;
			}
		}
	}

	public static function getTypesByPersonId($personId)
	{
		$sql = "SELECT `type`.`type_name` FROM  `person`,
		`type_person`,
		type
		WHERE `person`.`id` = :id
		&& `type_person`.`person_id` = `person`.`id`
		&& `type_person`.`type_id` = `type`.`id`";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":id", $personId);

		$retArr = array();

		$cmd->executeReader()->readAll(function($row) use (&$retArr)
		{
			$retArr[] = $row->type_name;
		});
			
		return $retArr;
	}

	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}

	public static function findPersonByName($naam,$voornaam)
	{

		$sql = "SELECT id FROM  `person`
		WHERE
		`person`.`first_name` = :firstname
		AND `person`.`name` = :name";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":firstname", $voornaam);
		$cmd->addParam(":name", $naam);

		$reader = $cmd->executeReader();

		$retObj = $reader->read();
			
		if($retObj === false)
		{
			return NULL;
		}else{
			return self::getPersonById($retObj->id);
		}
	}
	
	
	
	public static function getPersonByUniqueIdentifier($uniqueIdentifier)
	{
		$sql = "SELECT id FROM  `person`
		WHERE
		`person`.`uniqueIdentifier` = :uniqueidentifier";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":uniqueidentifier", $uniqueIdentifier);
		
		$reader = $cmd->executeReader();
		
		$retObj = $reader->read();
			
		if($retObj === false)
		{
			return NULL;
		}else{
			return self::getPersonById($retObj->id);
		}
	}

	public function getYear()
	{
	    return $this->year;
	}

	public function setYear($year)
	{
	    $this->year = $year;
	}

	public function getUniqueIdentifier()
	{
	    return $this->uniqueIdentifier;
	}

	public function setUniqueIdentifier($uniqueIdentifier)
	{
	    $this->uniqueIdentifier = $uniqueIdentifier;
	}

	public function getInformatId()
	{
	    return $this->informatId;
	}

	public function setInformatId($informatId)
	{
	    $this->informatId = $informatId;
	}
	
	public static function getPersonIdsByGroup($groupid)
	{
		$sql = "SELECT
				p.`id`
				FROM `person` p
				WHERE p.`group_id` = :groupId";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":groupId", $groupid);
		
		$personidarr = array();
		
		$cmd->executeReader()->readAll(function($row) use (&$personidarr){
		
			$personidarr[] = $row->id;
		});
		
		return $personidarr;
	}
	
	/**
	 * seperate statement for performance
	 * call $person->setgroupid and then this
	 * moves person to new group id
	 * @param Person $person
	 */
	public static function moveUser($person)
	{
		$sql = "UPDATE  `person` SET
		`group_id` = :group_id
		WHERE id = :id;";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->newQuery($sql);
		$cmd->addParam(":id", $person->getId());
		$cmd->addParam(":group_id", $person->getGroupId());
		$cmd->execute();
	}

	public function getValErrors()
	{
	    return $this->valErrors;
	}

	public function getHasAdAccount()
	{
	    return $this->hasAdAccount;
	}

	public function getHasSSAccount()
	{
	    return $this->hasSSAccount;
	}

	public function getHasGaAccount()
	{
	    return $this->hasGaAccount;
	}
}



?>
