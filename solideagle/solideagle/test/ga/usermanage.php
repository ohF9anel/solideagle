<?php

namespace solideagle\test\ga;

use solideagle\plugins\ga\manageuser;
use solideagle\data_access\Person;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


//manageuser::addUser(Person::getPersonById(155));
manageuser::addUserToOu(Person::getPersonById(155));
//manageuser::updateUser(Person::getPersonById(155), "gammer");
//manageuser::removeUser(Person::getPersonById(155));
//manageuser::getUser(Person::getPersonById(155));
?>
