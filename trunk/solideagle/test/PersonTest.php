<?php

require_once '../data_access/Person.php';

use DataAccess\Person;

//$person = new Person();
//
//$person->setId("1");
//$person->setAccountUsername("bodsonb");
//$person->setAccountPassword("pass");
//$person->setAccountActive(true);
//$person->setAccountActiveUntill("20120328");
//$person->setAccountActiveFrom("00000000");
//$person->setFirstName("Bruno");
//$person->setName("Bodson");
//$person->setGender("M");
//$person->setBirthDate("19890102");
//$person->setBirthPlace("Gent");
//$person->setNationality("Belg");
//$person->setStreet("Brusselsesteenweg");
//$person->setHouseNumber("146");
//$person->setPostCode("9090");
//$person->setCity("Melle");
//$person->setCountry("België");
//$person->setEmail("bodsonb@dbz.be");
//$person->setPhone("+3292222222");
//$person->setPhone2("+3292333333");
//$person->setMobile("+32473365305");
//$person->setMadeOn("201203021445");
//$person->setGroupId("1");
//$person->setOtherInformation("andere info \n dfsjkdsj");
//$person->setDeleted(false);
//$person->setStudentPreviousSchool("Voskenslaan");
//$person->setStudentStamNr("12345678");
//$person->setParentOccupation("Stripper");
//
//$id = Person::updatePerson($person);

Person::removePersonById(3);

//echo $id;
echo "finish";

?>
