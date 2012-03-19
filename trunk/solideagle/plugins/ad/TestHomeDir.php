<?php

namespace AD;

require_once('HomeFolder.php');
require_once('data_access/Group.php');

use DataAccess\Group;

$group[] = Group::getGroupById(76);
$group[] = Group::getGroupById(78);

HomeFolder::moveHomeFolder('S1', 'C:\homefolders\admins', 'S1', 'C:\homefolders', 'bodsonb', $group);
//HomeFolder::createHomeFolder('S1', 'C:\homefolders\admins', 'bodsonb', $group);
//HomeFolder::removeHomeFolder('S1', 'C:\homefolders\admins', 'bodsonb')

?>


