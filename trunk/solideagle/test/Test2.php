<?php

require_once 'data_access/TaskQueue.php';
use DataAccess\TaskQueue;
require_once 'data_access/Person.php';
//require_once 'scripts/smartschool/usermanager.php';
require_once 'scripts/ad/usermanager.php';
require_once 'plugins/smartschool/data_access/SSUser.php';
use DataAccess\Person;
use scripts\ad\usermanager;
//use smartschoolplugin\usermanager;
//use Smartschool\SSUser;

//$person = Person::getPersonById(85);

//$ssuser = SSUser::convertPersonToSsUser(Person::getPersonById(85));

$uman = new usermanager(29, 85);
//
$uman->prepareAddHomeFolder('10.3.7.111', 'kellyb121', 'C:\homefolders\leerlingen\12', 'C:\scans', 'C:\www', 'C:\downloads', 'C:\uploads');
//$uman->prepareAddSsUser($ssuser);

//require_once 'scripts/deamon.php';

?>
