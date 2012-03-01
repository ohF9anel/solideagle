<?php

class PersonRevision
{

    // variables
    private $id;
    private $version_id;
    private $type;
    private $account_username;
    private $account_password;
    private $account_active;
    private $account_active_untill;
    private $account_active_from;
    private $start_date;
    private $first_name;
    private $name;
    private $gender;
    private $birth_date;
    private $birth_place;
    private $nationality;
    private $street;
    private $house_number;
    private $post_code;
    private $city;
    private $country;
    private $email;
    private $phone;
    private $phone2;
    private $mobile;
    private $made_on;
    private $group_id;
    private $information;
    private $deleted;
    private $teacher_course_id;
    private $student_previous_school;
    private $student_stamnr;
    private $parent_occupation;

    // getters & setters

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getVersion_id()
    {
        return $this->version_id;
    }

    public function setVersion_id($version_id)
    {
        $this->version_id = $version_id;
    }

    public function getType()
    {
        return $this->type;
    }

    public function setType($type)
    {
        $this->type = $type;
    }

    public function getAccount_username()
    {
        return $this->account_username;
    }

    public function setAccount_username($account_username)
    {
        $this->account_username = $account_username;
    }

    public function getAccount_password()
    {
        return $this->account_password;
    }

    public function setAccount_password($account_password)
    {
        $this->account_password = $account_password;
    }

    public function getAccount_active()
    {
        return $this->account_active;
    }

    public function setAccount_active($account_active)
    {
        $this->account_active = $account_active;
    }

    public function getAccount_active_untill()
    {
        return $this->account_active_untill;
    }

    public function setAccount_active_untill($account_active_untill)
    {
        $this->account_active_untill = $account_active_untill;
    }

    public function getAccount_active_from()
    {
        return $this->account_active_from;
    }

    public function setAccount_active_from($account_active_from)
    {
        $this->account_active_from = $account_active_from;
    }

    public function getStart_date()
    {
        return $this->start_date;
    }

    public function setStart_date($start_date)
    {
        $this->start_date = $start_date;
    }

    public function getFirst_name()
    {
        return $this->first_name;
    }

    public function setFirst_name($first_name)
    {
        $this->first_name = $first_name;
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

    public function getBirth_date()
    {
        return $this->birth_date;
    }

    public function setBirth_date($birth_date)
    {
        $this->birth_date = $birth_date;
    }

    public function getBirth_place()
    {
        return $this->birth_place;
    }

    public function setBirth_place($birth_place)
    {
        $this->birth_place = $birth_place;
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

    public function getHouse_number()
    {
        return $this->house_number;
    }

    public function setHouse_number($house_number)
    {
        $this->house_number = $house_number;
    }

    public function getPost_code()
    {
        return $this->post_code;
    }

    public function setPost_code($post_code)
    {
        $this->post_code = $post_code;
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

    public function getMade_on()
    {
        return $this->made_on;
    }

    public function setMade_on($made_on)
    {
        $this->made_on = $made_on;
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

    public function getTeacher_course_id()
    {
        return $this->teacher_course_id;
    }

    public function setTeacher_course_id($teacher_course_id)
    {
        $this->teacher_course_id = $teacher_course_id;
    }

    public function getStudent_previous_school()
    {
        return $this->student_previous_school;
    }

    public function setStudent_previous_school($student_previous_school)
    {
        $this->student_previous_school = $student_previous_school;
    }

    public function getStudent_stamnr()
    {
        return $this->student_stamnr;
    }

    public function setStudent_stamnr($student_stamnr)
    {
        $this->student_stamnr = $student_stamnr;
    }

    public function getParent_occupation()
    {
        return $this->parent_occupation;
    }

    public function setParent_occupation($parent_occupation)
    {
        $this->parent_occupation = $parent_occupation;
    }

}

?>
