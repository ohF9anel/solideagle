<?php

require_once '../config.php';

require_once '../data_access/Group.php';




use DataAccess\Group;

$group = new Group();

$group->setName("TheBigRoot");
$group->setDescription("Just testing");

//var_dump( Group::isValidGroup($group));

//var_dump(Group::validateGroup($group));

$group->setName("");
$group->setDescription("Just testing");

Group::addGroup($group);

//var_dump( Group::isValidGroup($group));

//var_dump(Group::validateGroup($group));

$group->setName("sqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsdsqdfqsd");
$group->setDescription("Just testing");

//var_dump( Group::isValidGroup($group));

//var_dump(Group::validateGroup($group));


die();

$childGroup = new Group();

$childGroup->setName("TheBigLeaf");
$childGroup->setDescription("Just testing leafs");

$childGroupGroup = new Group();

$childGroupGroup->setName("TheBigLeafLeaf");
$childGroupGroup->setDescription("Just testing leaf leafs");


$childGroup->addChildGroup($childGroupGroup);

$group->addChildGroup($childGroup);

Group::addGroup($group);



// select group_concat(n.name order by n.id separator ' -> ') as path
// from group_closure d
// join group_closure a on (a.child_id = d.child_id)
// join `group` n on (n.id = a.parent_id)
// where d.parent_id = 34 and d.child_id != d.parent_id
// group by d.child_id;

?>
