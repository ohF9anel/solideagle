<?php

include_once 'data_access/Person.php';
use DataAccess\Person;


$person = new Person();
$person->setName("bodson");
$person->setFirstName("b");

echo Person::tryCreateUsername($person);


?>
