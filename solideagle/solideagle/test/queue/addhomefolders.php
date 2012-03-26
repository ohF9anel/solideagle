<?php

namespace solideagle\test\queue;

use solideagle\scripts\ad\usermanager;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

//$person = Person::getPersonById(85);

//$ssuser = SSUser::convertPersonToSsUser(Person::getPersonById(85));

usermanager::prepareAddHomeFolder('85', '10.3.7.111', 'kellyb121', 'C:\homefolders\leerlingen\12', 'C:\scans', 'C:\www', 'C:\downloads', 'C:\uploads');
//$uman->prepareAddSsUser($ssuser);

//require_once 'scripts/deamon.php';

?>
