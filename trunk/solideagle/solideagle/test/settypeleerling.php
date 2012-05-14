<?php

namespace solideagle\test;

use solideagle\data_access\Person;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


for ($i = 10771; $i < 12436; $i++)
{
    var_dump("settype: " . Person::setTypeLeerling($i));
}

?>
