<?php

require_once 'data_access/Group.php';



$conf = array('mode' => 0600, 'timeFormat' => '%X %x');
$logger = Log::singleton('file', 'out.log', 'ident', $conf);
for ($i = 0; $i < 10; $i++) {
	$logger->log("Log entry $i");
}

die();

use DataAccess\Group;

$group = new Group();

$group->setName("TheBigRoot");
$group->setDescription("Just testing");


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
