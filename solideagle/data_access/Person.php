<?php




namespace DataAccess
{
	function IsNullOrEmptyString($question){
		return (!isset($question) || trim($question)==='');
	}

    require_once 'data_access/database/databasecommand.php';
    require_once 'data_access/validation/Validator.php';
    require_once 'data_access/Type.php';
    require_once 'data_access/Group.php';
    require_once 'logging/Logger.php';
    use Database\DatabaseCommand;
    use Validation\Validator;
    use Validation\ValidationError;
    use Logging\Logger;
    
    class Person
    {

        // variables
        private $id;
        private $groups = array();
        private $accountUsername;
        private $accountPassword;
        private $accountActive = 1;
        private $accountActiveUntill  = "";
        private $accountActiveFrom  = "";
        private $startDate;
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
        
        private $valErrors = array();

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

        public function getTypes()
        {
            return $this->type;
        }

        public function addType($type)
        {
            $this->type[] = $type;
        }
        
        public function getGroups()
        {
            return $this->groups;
        }

        public function addGroup($group)
        {
            $this->groups[] = $group;
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

        public function getAccountActive()
        {
            return $this->accountActive;
        }

        public function setAccountActive($accountActive)
        {
            $this->accountActive = $accountActive;
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

        public function getStartDate()
        {
            return $this->startDate;
        }

        public function setStartDate($startDate)
        {
            $this->startDate = $startDate;
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
        
        /**
         * 
         * 
         *
         * @param Person $person
         */
        
        private static function tryCreateUsername($person)
        {
        	
        	$counter = "";
        	
        	$username = $person->getName() . substr($person->getFirstName(),0,1) . date("y");
        	
        	$username = strtolower($username);
        	
        	$sql = "select account_username from person where account_username = :accusername";
        	
        	for(;;)
        	{
        		echo "trying: " . $username . $counter . "\n";
        		
        		$cmd = new DatabaseCommand($sql);
        		 
        		$cmd->addParam(":accusername", $username . $counter);
        		 
        		if($cmd->executeReader()->read())
        		{
        			$counter += 1;
        		}else{
        			break;
        		}
        	}
        	
        	return $username . $counter;
        }

        /**
         *
         * @param Person $this
         * @return int id
         */
        public static function addPerson($person)
        {
        	
        		$person->setAccountUsername(Person::tryCreateUsername($person));
        		
        		$person->setAccountPassword("P@ssw0rd");
        	
                $err = Person::validatePerson($person);
                if (!empty($err))
                {
                    Logger::getLogger()->log("Person not validated before saving! Validation errors:\n" . var_export($err,true) . "\nPerson object dump:\n" . var_export($person,true) . "\n",PEAR_LOG_ERR);
                    return false;
                }
                
                if (sizeof($person->getTypes()) < 1)
                {
                    Logger::getLogger()->log("Person does not have any type(s)! \nPerson object dump:\n" . var_export($person,true) . "\n",PEAR_LOG_ERR);
                    return false;
                }
                
                $sql = "INSERT INTO `CentralAccountDB`.`person`
                        (`id`,
                        `account_username`,
                        `account_password`,
                        `account_active`,
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
                        `parent_occupation`)
                        VALUES
                        (
                        :id,
                        :account_username,
                        :account_password,
                        :account_active,
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
                        :parent_occupation
                        );
                        ";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $person->getId());
                $cmd->addParam(":account_username", $person->getAccountUsername());
                $cmd->addParam(":account_password", $person->getAccountPassword());
                $cmd->addParam(":account_active", $person->getAccountActive());
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

                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $personId =  $cmd->executeScalar();
                
                // add person to correct type(s)
                
                $sql = "INSERT INTO `CentralAccountDB`.`type_person`
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
                        $cmd = new DatabaseCommand($sql);
                        $cmd->addParam(":type_id", $type->getId());
                        $cmd->addParam(":person_id", $personId);

                        $cmd->execute();
                }
                
                // add person to correct group(s)
                                
                $sql = "INSERT INTO `CentralAccountDB`.`group_person`
                                (
                                `group_id`,
                                `person_id`
                                )
                                VALUES
                                (
                                :group_id,
                                :person_id
                                );";
                
                foreach($person->getGroups() as $group)
                {
                        $cmd = new DatabaseCommand($sql);
                        $cmd->addParam(":group_id", $group->getId());
                        $cmd->addParam(":person_id", $personId);

                        $cmd->execute();
                }

                $cmd->CommitTransaction();

                return $personId;
        }
        
        /**
         *
         * @param Person $person 
         */
        public static function updatePerson($person)
        {
                $err = Person::validatePerson($person);
                if (!empty($err))
                {
                    Logger::getLogger()->log("Person not validated before saving! Validation errors:\n" . var_export($err,true) . "\nPerson object dump:\n" . var_export($person,true) . "\n",PEAR_LOG_ERR);
                    return false;
                }
                
                if (sizeof($person->getTypes()) < 1)
                {
                    Logger::getLogger()->log("Person does not have any type(s)! \nPerson object dump:\n" . var_export($person,true) . "\n",PEAR_LOG_ERR);
                    return false;
                }
                
                // save old data to person_revision
                
                $sql = "SELECT * FROM `CentralAccountDB`.`person`
                        WHERE `id` = :id;";
                
                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $person->getId());

                $reader = $cmd->executeReader();
                
                $retObj = $reader->read();
                
                $oldPerson = new Person();

                $oldPerson->setId($retObj->id);
                $oldPerson->setAccountUsername($retObj->account_username);
                $oldPerson->setAccountPassword($retObj->account_password);
                $oldPerson->setAccountActive($retObj->account_active);
                $oldPerson->setAccountActiveUntill($retObj->account_active_untill);
                $oldPerson->setAccountActiveFrom($retObj->account_active_from);
                $oldPerson->setFirstName($retObj->first_name);
                $oldPerson->setName($retObj->name);
                $oldPerson->setGender($retObj->gender);
                $oldPerson->setBirthDate($retObj->birth_date);
                $oldPerson->setBirthPlace($retObj->birth_place);
                $oldPerson->setNationality($retObj->nationality);
                $oldPerson->setStreet($retObj->street);
                $oldPerson->setHouseNumber($retObj->house_number);
                $oldPerson->setPostCode($retObj->post_code);
                $oldPerson->setCity($retObj->city);
                $oldPerson->setCountry($retObj->country);
                $oldPerson->setEmail($retObj->email);
                $oldPerson->setPhone($retObj->phone);
                $oldPerson->setPhone2($retObj->phone2);
                $oldPerson->setMobile($retObj->mobile);
                $oldPerson->setMadeOn($retObj->made_on);
                $oldPerson->setOtherInformation($retObj->other_information);
                $oldPerson->setDeleted($retObj->deleted);
                $oldPerson->setStudentPreviousSchool($retObj->student_previous_school);
                $oldPerson->setStudentStamnr($retObj->student_stamnr);
                $oldPerson->setParentOccupation($retObj->parent_occupation);
                
                // no difference, no update!
                if($oldPerson == $person)
                    return false;     

                $sql = "INSERT INTO `CentralAccountDB`.`person_revision`
                        (
                        `id`,
                        `account_username`,
                        `account_password`,
                        `account_active`,
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
                        `parent_occupation`)
                        VALUES
                        (
                        :id,
                        :account_username,
                        :account_password,
                        :account_active,
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
                        :parent_occupation
                        );
                        ";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $oldPerson->getId());
                $cmd->addParam(":account_username", $oldPerson->getAccountUsername());
                $cmd->addParam(":account_password", $oldPerson->getAccountPassword());
                $cmd->addParam(":account_active", $oldPerson->getAccountActive());
                $cmd->addParam(":account_active_untill", $oldPerson->getAccountActiveUntill());
                $cmd->addParam(":account_active_from", $oldPerson->getAccountActiveFrom());
                $cmd->addParam(":first_name", $oldPerson->getFirstName());
                $cmd->addParam(":name", $oldPerson->getName());
                $cmd->addParam(":gender", $oldPerson->getGender());
                $cmd->addParam(":birth_date", $oldPerson->getBirthDate());
                $cmd->addParam(":birth_place", $oldPerson->getBirthPlace());
                $cmd->addParam(":nationality", $oldPerson->getNationality());
                $cmd->addParam(":street", $oldPerson->getStreet());
                $cmd->addParam(":house_number", $oldPerson->getHouseNumber());
                $cmd->addParam(":post_code", $oldPerson->getPostCode());
                $cmd->addParam(":city", $oldPerson->getCity());
                $cmd->addParam(":country", $oldPerson->getCountry());
                $cmd->addParam(":email", $oldPerson->getEmail());
                $cmd->addParam(":phone", $oldPerson->getPhone());
                $cmd->addParam(":phone2", $oldPerson->getPhone2());
                $cmd->addParam(":mobile", $oldPerson->getMobile());
                $cmd->addParam(":made_on", $oldPerson->getMadeOn());
                $cmd->addParam(":other_information", $oldPerson->getOtherInformation());
                $cmd->addParam(":deleted", $oldPerson->getDeleted());
                $cmd->addParam(":student_previous_school", $oldPerson->getStudentPreviousSchool());
                $cmd->addParam(":student_stamnr", $oldPerson->getStudentStamNr());
                $cmd->addParam(":parent_occupation", $oldPerson->getParentOccupation());
                
                $cmd->BeginTransaction();

                $cmd->execute();
                
                // update new person's data
                
                $sql = "UPDATE `CentralAccountDB`.`person` SET
                        `id` = :id,
                        `account_username` = :account_username,
                        `account_password` = :account_password,
                        `account_active` = :account_active,
                        `account_active_untill` = :account_active_untill,
                        `account_active_from` = :account_active_from,
                        `first_name` = :first_name,
                        `name` = :name,
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
                        `made_on` = :made_on,
                        `other_information` = :other_information,
                        `deleted` = :deleted,
                        `student_previous_school` = :student_previous_school,
                        `student_stamnr` = :student_stamnr,
                        `parent_occupation` = :parent_occupation
                        WHERE id = :id;
                        ";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $person->getId());
                $cmd->addParam(":account_username", $person->getAccountUsername());
                $cmd->addParam(":account_password", $person->getAccountPassword());
                $cmd->addParam(":account_active", $person->getAccountActive());
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

                $cmd->execute();
                
                // updates person's type(s)
                
                $sql = "DELETE FROM `CentralAccountDB`.`type_person`
					WHERE `person_id` = :personId;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":personId", $person->getId());

                $cmd->execute();
                
                $sql = "INSERT INTO `CentralAccountDB`.`type_person`
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
                        $cmd = new DatabaseCommand($sql);
                        $cmd->addParam(":type_id", $type->getId());
                        $cmd->addParam(":person_id", $person->getId());

                        $cmd->execute();
                }
                
                // update person's group(s)
                
                $sql = "DELETE FROM `CentralAccountDB`.`group_person`
					WHERE `person_id` = :personId;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":personId", $person->getId());

                $cmd->execute();
                
                $sql = "INSERT INTO `CentralAccountDB`.`group_person`
                                (
                                `group_id`,
                                `person_id`
                                )
                                VALUES
                                (
                                :group_id,
                                :person_id
                                );";
                
                foreach($person->getGroups() as $group)
                {
                        $cmd = new DatabaseCommand($sql);
                        $cmd->addParam(":group_id", $group->getId());
                        $cmd->addParam(":person_id", $person->getId());

                        $cmd->execute();
                }
                
                $cmd->CommitTransaction();
        }

        public static function delPersonById($personId)
        {
                //$sql = "DELETE FROM `CentralAccountDB`.`type_person`
            // remove links tussentabel type!!
            
                $sql = "DELETE FROM `CentralAccountDB`.`person`
					WHERE `id` = :id;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $personId);

                $cmd->execute();
        }

        public static function getPersonById($id)
        {
                $sql = "SELECT * FROM `CentralAccountDB`.`person`
                        WHERE `id` = :id;";
                
                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $id);

                $reader = $cmd->executeReader();
                
                $retObj = $reader->read();

                $person = new Person();
                
                $person->setId($retObj->id);
                $person->setAccountUsername($retObj->account_username);
                $person->setAccountPassword($retObj->account_password);
                $person->setAccountActive($retObj->account_active);
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
                
                $sql = "SELECT `type`.`id`, `type`.`type_name` FROM `CentralAccountDB`.`type_person`,
                       `CentralAccountDB`.`type`
                        WHERE `person_id` = :person_id
                        AND `type`.`id` = `type_person`.`type_id`
                        ";
                
                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":person_id", $person->getId());
  
                $cmd->executeReader()->readAll(function($row) use (&$person) {
			$type = new Type($row->id, $row->type_name);
			$person->addType($type);
		});
                
                $sql = "SELECT `group`.`id`, `group`.`name` FROM `CentralAccountDB`.`group`,
                       `CentralAccountDB`.`group_person`
                        WHERE `person_id` = :person_id
                        AND `group`.`id` = `group_person`.`group_id`
                        ";
                
                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":person_id", $person->getId());

                $cmd->executeReader()->readAll(function($row) use (&$person) {
			$group = new Group($row->id, $row->name);
			$person->addGroup($group);
		});
               
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
                        FROM `CentralAccountDB`.`person`,
                        `CentralAccountDB`.`type_person`,
                        `CentralAccountDB`.`type`
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
        
        public static function validatePerson($person)
        {
            $validationErrors = array();
           
            // account username
            $valErrors = Validator::validateString($person->getAccountUsername(), 0, 45, false);
            foreach ($valErrors as $valError)
            {
                switch($valError) {
                    case ValidationError::STRING_TOO_LONG:
                        $validationErrors[] = "Gebruikersnaam: mag niet langer zijn dan 45 karakters."; break;
                    case ValidationError::STRING_TOO_SHORT:
                        $validationErrors[] = "Gebruikersnaam: niet ingevoerd."; break;
                    case ValidationError::STRING_HAS_SPECIAL_CHARS:
                        $validationErrors[] = "Gebruikersnaam: mag geen speciale tekens bevatten."; break;
                    default:
                        $validationErrors[] = "Gebruikersnaam: fout."; break;
                }
            }
            
            // account password
            $valErrors = Validator::validatePassword($person->getAccountPassword(), 0, 64, true, true, true);
            foreach ($valErrors as $valError)
            {
                switch($valError) {
                    case ValidationError::STRING_TOO_LONG:
                        $validationErrors[] = "Wachtwoord: mag niet langer zijn dan 45 karakters.";
                        break;
                    case ValidationError::STRING_TOO_SHORT:
                        $validationErrors[] = "Wachtwoord: moet langer zijn dan 8 karakters.";
                        break;
                    case ValidationError::PSW_NO_LOWER_CASE:
                        $validationErrors[] = "Wachtwoord: moet een kleine letter bevatten.";
                        break;
                    case ValidationError::PSW_NO_UPPER_CASE:
                        $validationErrors[] = "Wachtwoord: moet een hoofdletter bevatten.";
                        break;
                    case ValidationError::PSW_NO_NUMBER:
                        $validationErrors[] = "Wachtwoord: moet een nummer bevatten.";
                        break;
                    default:
                        $validationErrors[] = "Wachtwoord: fout."; break;
                }
            }
            if(!IsNullOrEmptyString($person->getAccountActiveUntill()))
            {
            // account active untill
            $valErrors = Validator::validateDateTime($person->getAccountActiveUntill(), true);
            foreach ($valErrors as $valError)
            {
                switch($valError) {
                    case ValidationError::DATE_BAD_SYNTAX;
                        $validationErrors[] = "Account actief tot: tijdstip moet ingegeven worden als YYYYMMDDHHMMSS.";
                        break;
                    case ValidationError::DATE_DOES_NOT_EXIST;
                        $validationErrors[] = "Account actief tot: deze datum bestaat niet.";
                        break;
                    case ValidationError::TIME_DOES_NOT_EXIST;
                        $validationErrors[] = "Account actief tot: dit tijdstip bestaat niet.";
                        break;
                    default:
                        $validationErrors[] = "Account actief tot: fout."; break;
                }
            }
        }
        
            if(!IsNullOrEmptyString($person->getAccountActiveFrom()))
            {
            // account active from
            $valErrors = Validator::validateDateTime($person->getAccountActiveFrom(), true);
            foreach ($valErrors as $valError)
            {
                switch($valError) {
                    case ValidationError::DATE_BAD_SYNTAX;
                        $validationErrors[] = "Account actief vanaf: Tijdstip moet ingegeven worden als YYYYMMDDHHMMSS.";
                        break;
                    case ValidationError::DATE_DOES_NOT_EXIST;
                        $validationErrors[] = "Account actief vanaf: Deze datum bestaat niet.";
                        break;
                    case ValidationError::TIME_DOES_NOT_EXIST;
                        $validationErrors[] = "Account actief vanaf: Dit tijdstip bestaat niet.";
                        break;
                    default:
                        $validationErrors[] = "Account actief vanaf: fout."; break;
                }
            }}
            
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
            }}
            
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
                    case ValidationError::INVALID_EMAIL_ADDRESS:
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
        	$sql = "SELECT DISTINCT
					`person`.`id`,
					`person`.`account_username`,
					`person`.`account_active`,
					`person`.`first_name`,
					`person`.`name`,
					`person`.`made_on`
					FROM `CentralAccountDB`.`person`, `CentralAccountDB`.`group_person`
					WHERE
					((:groupid = -1) OR (`person`.`id` = `group_person`.`person_id` ))
					AND
					`person`.`deleted` = 0
					AND
					((:groupid = -1) OR (`group_person`.`group_id` =  :groupid))";
        	
        	$cmd = new DatabaseCommand($sql);
        	
        	$cmd->addParam(":groupid", $groupid);
        	
        	$retarr = array();
        	
        	$cmd->executeReader()->readAll(function($row) use (&$retarr){
        		
        		$tempperson = new Person();
        		$tempperson->setId($row->id);
        		$tempperson->setName($row->name);
        		$tempperson->setFirstName($row->first_name);
        		$tempperson->setAccountUsername($row->account_username);
        		$tempperson->setAccountActive($row->account_active);
        		$tempperson->setMadeOn($row->made_on);
        		
        		$retarr[] = $tempperson;
        	
        	});
        	
        	
        	return $retarr;
        }

    }
    
}

?>
