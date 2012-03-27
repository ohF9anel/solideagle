<?php

namespace solideagle\test\ad;

use solideagle\plugins\ad\managegroup;
use solideagle\data_access\Group;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$child = Group::getGroupById(84);
var_dump($child);
$memberof = Group::getParents($child);
if ($memberof != null)
{
    $memberof = is_array($memberof) ? $memberof[0] : $memberof;
}

var_dump($memberof);

managegroup::addGroup($child, $memberof);

?>
