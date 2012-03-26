<?php

require_once 'scripts/ad/usermanager.php';
use scripts\ad\usermanager;

//$person = Person::getPersonById(85);

//$ssuser = SSUser::convertPersonToSsUser(Person::getPersonById(85));

usermanager::prepareAddHomeFolder('85', '10.3.7.111', 'kellyb121', 'C:\homefolders\leerlingen\12', 'C:\scans', 'C:\www', 'C:\downloads', 'C:\uploads');
//$uman->prepareAddSsUser($ssuser);

//require_once 'scripts/deamon.php';

?>
