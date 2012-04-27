<?php

namespace solideagle\test;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

use solideagle\plugins\ad\HomeFolder;

//$group[] = Group::getGroupById(76);
//$group[] = Group::getGroupById(78);

$conn = \solideagle\plugins\ad\SSHManager::singleton()->getConnection('S1.solideagle.lok');
var_dump($conn);

if (\solideagle\plugins\ad\HomeFolder::createHomeFolder($conn, 'S1.solideagle.lok', 'C:\homefolders\leerlingen\12', 'kellyb121'));
    $conn->exitShell();

//HomeFolder::createHomeFolder('S1', 'C:\homefolders\admins', 'bodsonb', $group);
//HomeFolder::removeHomeFolder('S1', 'C:\homefolders\admins', 'bodsonb')

?>


