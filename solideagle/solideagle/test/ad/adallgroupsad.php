<?php

namespace solideagle\test\ad;

use solideagle\data_access\Group;
use solideagle\scripts\ad\oumanager;
use solideagle\scripts\ad\groupmanager;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$groups = Group::getAllGroups();

foreach ($groups as $newgroup)
{
    $parents = Group::getParents($newgroup);
    oumanager::prepareAddOu($parents, $newgroup);
    groupmanager::prepareAddGroup($parents,$newgroup);
}


?>
