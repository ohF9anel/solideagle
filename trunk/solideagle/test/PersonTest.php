<?php

require_once '../data_access/Person.php';
require_once '../data_access/Type.php';
require_once '../data_access/Group.php';

use DataAccess\Person;
use DataAccess\Group;
use DataAccess\Type;
use Utils\UsernameGenerator;

$person = new Person();

$person->addType(new Type(1, null));

//$person->setId(58);
$person->setAccountUsername("bruno");
$person->setAccountPassword("Azerty123");
$person->setAccountActive(true);
$person->setAccountActiveUntill("20120305151546");
$person->setAccountActiveFrom("20120304111111");
$person->setFirstName("bier");
$person->setName("kelly");
$person->setGender("M");
$person->setBirthDate("19890102");
$person->setBirthPlace("Gent");
$person->setNationality("Belg");
$person->setStreet("Brusselsesteenweg");
$person->setHouseNumber("146");
$person->setPostCode("9090");
$person->setCity("Melle");
$person->setCountry("België");
$person->setEmail("bodsonb@dbz.be");
$person->setPhone("+3292222222");
$person->setPhone2("+3292333333");
$person->setMobile("+32473365305");
$person->setMadeOn("20120301144511");
$person->setOtherInformation("andere info \n dfsjkdsj");
$person->setDeleted(false);
$person->setStudentPreviousSchool("Voskenslaan");
$person->setStudentStamNr("12345678");
$person->setParentOccupation("Stripper");
$person->setGroupId(84);
//
$errors = Person::validatePerson($person);

var_dump($errors);
$id = Person::addPerson($person);


echo $id;
echo "finish";

?>
