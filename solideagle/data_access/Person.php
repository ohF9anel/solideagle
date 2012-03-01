<?php

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
    private $group_id;
    private $information;
    private $deleted;
    private $teacherCourseId;
    private $studentPreviousSchool;
    private $studentStamnr;
    private $parentOccupation;

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

    public function getGroup_id()
    {
        return $this->group_id;
    }

    public function setGroup_id($group_id)
    {
        $this->group_id = $group_id;
    }

    public function getInformation()
    {
        return $this->information;
    }

    public function setInformation($information)
    {
        $this->information = $information;
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
    
    public function addPerson($this)
    {

    }

    public function updatePerson($this)
    {

    }

    public static function removePersonById($personId)
    {

    }

    public static function getPersonById($id)
    {

    }

}

?>
