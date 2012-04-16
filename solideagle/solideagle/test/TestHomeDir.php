<?php

namespace solideagle\test;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

use solideagle\plugins\ad\HomeFolder;

//$group[] = Group::getGroupById(76);
//$group[] = Group::getGroupById(78);

$test = new \solideagle\plugins\ad\ManageHomeFolder('192.168.1.21', 'kellyb121', 'C:\homefolders\leerlingen\12', 'C:\scans', 'C:\www', 'C:\downloads', 'C:\uploads');
$test->startHomeFolderManager();

//HomeFolder::createHomeFolder('S1', 'C:\homefolders\admins', 'bodsonb', $group);
//HomeFolder::removeHomeFolder('S1', 'C:\homefolders\admins', 'bodsonb')

?>


