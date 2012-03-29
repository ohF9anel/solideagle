<?php

namespace solideagle\test\queue;

use solideagle\scripts\ad\usermanager;
use solideagle\data_access\Person;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


usermanager::prepareDelUser(Person::getPersonById(85));
//$uman->prepareAddSsUser($ssuser);

//require_once 'scripts/deamon.php';

?>
