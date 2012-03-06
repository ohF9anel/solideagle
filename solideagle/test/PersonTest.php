<?php

require_once '../data_access/Person.php';
require_once '../data_access/Type.php';
require_once '../data_access/utils/UsernameGenerator.php';

use DataAccess\Person;
use DataAccess\Type;
use Utils\UsernameGenerator;

$person = new Person();

//$person->addType(new Type(1, null));
//$person->addType(new Type(2, null));

$person->setId(1);
$person->setAccountUsername("bodsonb");
$person->setAccountPassword("Azerty123");
$person->setAccountActive(true);
$person->setAccountActiveUntill("20120305151546");
$person->setAccountActiveFrom("20120304111111");
$person->setFirstName("Marie Michele");
$person->setName("De la Creme");
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
$person->setGroupId("1");
$person->setOtherInformation("andere info \n dfsjkdsj");
$person->setDeleted(false);
$person->setStudentPreviousSchool("Voskenslaan");
$person->setStudentStamNr("12345678");
$person->setParentOccupation("Stripper");
//
$errors = Person::validatePerson($person);

var_dump($errors);
$id = Person::updatePerson($person);


echo $id;
echo "finish";

?>
