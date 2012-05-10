<?php

namespace solideagle\test;

use solideagle\data_access\Person;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


$persons = Person::searchPerson("bruno", null, null);

var_dump($persons);

for ($i = 3110; $i < 4821; $i++)
{
    var_dump(Person::setRandomPassword($i));
}

?>
