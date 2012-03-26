<?php

require_once 'scripts/ad/usermanager.php';

use DataAccess\Person;
use DataAccess\Group;
use scripts\ad\usermanager;

$person = Person::getPersonById(85);

usermanager::prepareUpdateUser($person);

?>
