<?php

require_once 'data_access/TaskQueue.php';
use DataAccess\TaskQueue;
require_once 'scripts/ad/usermanager.php';
require_once 'plugins/ad/User.php';
require_once 'data_access/Person.php';
require_once 'data_access/Group.php';
use adscripts\usermanager;
use DataAccess\Person;
use DataAccess\Group;
use AD\User;



$person = Person::getPersonById(85);

$aduser = User::convertPersonToAdUser(Person::getPersonById(85));

$uman = new usermanager(29, 85);
//
//uman->prepareAddHomeFolder('10.3.7.111', 'kellyb121', 'C:\homefolders\leerlingen\12', 'C:\scans', 'C:\www', 'C:\downloads', 'C:\uploads');
//$uman->prepareAddUser($aduser->getUserInfo(), Group::getParents(Group::getGroupById($person->getGroupId())));

//require_once 'scripts/deamon.php';

?>
