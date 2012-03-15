<?php

namespace AD;

require_once('ConnectionLdap.php');
require_once('data_access/Group.php');

use DataAccess\Group;

$cgroup = Group::getGroupById(84);
$pgroup = Group::getParents($cgroup);

$conn = new ConnectionLDAP();

$conn->addOU($pgroup, $cgroup);

?>
