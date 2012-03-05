<?php

namespace DataAccess
{

    require_once '../data_access/database/databasecommand.php';
    require_once '../data_access/validation/Validator.php';
    use Database\DatabaseCommand;
    use Validation\Validator;
    
    class Person
    {

        // variables
        private $id;
        private $type;
        private $accountUsername;
        private $accountPassword;
        private $accountActive;
        private $accountActiveUntill;
        private $accountActiveFrom;
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
        private $madeOn;
        private $groupId;
        private $otherInformation;
        private $deleted;
        private $teacherCourseId;
        private $studentPreviousSchool;
        private $studentStamnr;
        private $parentOccupation;
        
        private $valErrors = array();

        // getters & setters

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }

        public function getType()
        {
            return $this->type;
        }

        public function setType($type)
        {
            $this->type = $type;
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

        public function getGroupId()
        {
            return $this->groupId;
        }

        public function setGroupId($groupId)
        {
            $this->groupId = $groupId;
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

        public function getTeacherCourseId()
        {
            return $this->teacherCourseId;
        }

        public function setTeacherCourseId($teacherCourseId)
        {
            $this->teacherCourseId = $teacherCourseId;
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
         * @param Person $this
         * @return int id
         */
        public static function addPerson($person)
        {
                if (!isValidPerson)
                    return false;
            
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
                        `group_id`,
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
                        :group_id,
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
                $cmd->addParam(":group_id", $person->getGroupId());
                $cmd->addParam(":other_information", $person->getOtherInformation());
                $cmd->addParam(":deleted", $person->getDeleted());
                $cmd->addParam(":student_previous_school", $person->getStudentPreviousSchool());
                $cmd->addParam(":student_stamnr", $person->getStudentStamNr());
                $cmd->addParam(":parent_occupation", $person->getParentOccupation());

                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $retval =  $cmd->executeScalar();

                $cmd->CommitTransaction();

                return $retval;
        }
        
        /**
         *
         * @param Person $person 
         */
        public static function updatePerson($person)
        {
                if (!isValidPerson)
                    return false;
                
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
                        `group_id` = :group_id,
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
                $cmd->addParam(":group_id", $person->getGroupId());
                $cmd->addParam(":other_information", $person->getOtherInformation());
                $cmd->addParam(":deleted", $person->getDeleted());
                $cmd->addParam(":student_previous_school", $person->getStudentPreviousSchool());
                $cmd->addParam(":student_stamnr", $person->getStudentStamNr());
                $cmd->addParam(":parent_occupation", $person->getParentOccupation());

                $cmd->execute();
        }

        public static function removePersonById($personId)
        {
                $sql = "DELETE FROM `CentralAccountDB`.`person`
					WHERE `id` = :id;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $personId);

                $cmd->execute();
        }

        public static function getPersonById($id)
        {
                
        }
        
        public static function validatePerson($person)
        {
            $validationErrors = array();
            
            $valErrors = Validator::validateString($person->getAccountUsername, 1, 45);
            
            foreach ($valErrors as $valError)
            {
                switch($valError) {
                    case ValidationError::STRING_TOO_LONG:
                        $validationErrors[] = "De gebruikersnaam mag niet langer zijn dan 45 karakters.";
                    case ValidationError::STRING_TOO_SHORT:
                        $validationErrors[] = "Voer een gebruikersnaam in.";
                    case ValidationError::STRING_HAS_SPECIAL_CHARS:
                        $validationErrors[] = "De gebruikers mag geen speciale tekens bevatten.";
                }
            }
            
            $valErrors = Validator::validateString($person->getPassword, 1, 45);
            
            foreach ($valErrors as $valError)
            {
                switch($valError) {
                    case ValidationError::STRING_TOO_LONG:
                        $validationErrors[] = "De gebruikersnaam mag niet langer zijn dan 45 karakters.";
                    case ValidationError::STRING_TOO_SHORT:
                        $validationErrors[] = "Voer een gebruikersnaam in.";
                    case ValidationError::STRING_HAS_SPECIAL_CHARS:
                        $validationErrors[] = "De gebruikers mag geen speciale tekens bevatten.";
                }
            }
        }
        
        public static function isValidPerson($person)
        {
            if (validatePerson($person))
            {
                
            }
            
            return true;
        }

    }
    
}

?>
