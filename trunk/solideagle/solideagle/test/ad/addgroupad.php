<?php

namespace solideagle\test\ad;

use solideagle\plugins\ad\managegroup;
use solideagle\data_access\Group;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$child = Group::getGroupById(7);
//var_dump($child);
var_dump("newChild :" . $child->getName());

$oldChild = Group::getGroupById(4);
var_dump("oldChild: " . $oldChild->getName());

$memberof = Group::getParents($oldChild);
if ($memberof != null)
{
    $memberof = is_array($memberof) ? $memberof[0] : $memberof;
}

$newParent = Group::getParents($child);
$newChildren = Group::getChilderen($child);



$oldParent = Group::getParents($oldChild);

$oldChildren = Group::getChilderen($oldChild);
//var_dump($oldChildren);

//var_dump($newParent);
//var_dump($oldParent);

//managegroup::addGroup($oldChild, $memberof);
//managegroup::modifyGroup($child, $oldchild);
//managegroup::modifyGroup($oldChild, $newParent[0], $newChildren, $oldParent[0], $oldChildren);

managegroup::removeGroup($child);

?>
