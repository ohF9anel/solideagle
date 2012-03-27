<?php

namespace solideagle\tests\queue;

use solideagle\scripts\ad\groupmanager;
use solideagle\data_access\Group;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$child = Group::getGroupById(84);

groupmanager::prepareAddGroup($child);



?>
