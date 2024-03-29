<?php

namespace solideagle\plugins\ad;

require_once('ManageOU.php');
require_once('data_access/Group.php');


set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

use DataAccess\Group;

//$cgroup = Group::getGroupById(84);
//$pgroup = Group::getParents($cgroup);
//
//$conn = new ConnectionLDAP();
//
//$conn->addOU($pgroup, $cgroup);

$oldGroup = Group::getGroupById(87);
$newGroup = Group::getGroupById(85);
$pOld = Group::getParents($oldGroup);
$pnew = Group::getParents($newGroup);

//var_dump($oldGroup);
//var_dump($oldGroup);
//var_dump($pOld);

//$pGroups = Group::getParents($group);
ManageOU::updateOU($pnew, $pOld, $newGroup, $oldGroup);
//ManageOU::updateOU($pnew, $pOld, $newGroup, $oldGroup);



?>
