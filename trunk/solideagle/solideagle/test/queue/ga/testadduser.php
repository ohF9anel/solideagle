<?php

namespace solideagle\test\queue\ga;

use solideagle\scripts\ga\usermanager;
use solideagle\data_access\Person;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


usermanager::prepareAddUser(Person::getPersonById(155));

?>
