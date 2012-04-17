<?php

namespace solideagle\test\ga;

use solideagle\plugins\ga\manageou;
use solideagle\data_access\Group;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$oldgroup = Group::getGroupById(4);
$newgroup = Group::getGroupById(3);
$oldparents = Group::getParents($oldgroup);
$newparents = Group::getParents($newgroup);

//var_dump($parents);

//manageou::addOU($oldgroup, $oldparents);
//manageou::moveOU($oldgroup, $oldparents, $newparents);
//manageou::updateOU($oldgroup, $oldgroup, $oldparents);
manageou::removeOU($oldgroup, $oldparents);

?>
