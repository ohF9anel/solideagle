<?php
require_once 'data_access/Group.php';
require_once 'scripts/ad/groupmanager.php';

use adplugin\groupmanager;
use DataAccess\Group;

$group = new Group();

$group->setName("TaskTest2(delete me later)");
$group->setParentId("75");
$group->setDescription("Task test");

$gid = Group::addGroup($group);

$group->setId($gid);

$gman = new groupmanager(27, $gid);

$gman->prepareAddGroup(Group::getParents($group),$group);

?>
