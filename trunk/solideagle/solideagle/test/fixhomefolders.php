<?php

namespace solideagle\scripts;

use solideagle\data_access\Group;

use solideagle\data_access\PlatformAD;

use solideagle\data_access\platforms;

use solideagle\data_access\Person;
use solideagle\plugins\ad\ManageUser;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


$persons = Person::getPersonIdsByGroupId(6);
var_dump($persons);

foreach($persons as $pid)
{
    $p = Person::getPersonById($pid);
    $username = $p->getAccountUsername();
    $year = ereg_replace("[^0-9]", "",$username);
    $year = substr($year, 0, 2);
    var_dump("\\\\atlas4\\homefolders\\leerlingen\\" . $year . "\\" . $username);
    //ManageUser::setHomeFolder($p->getAccountUsername(), "\\\\atlas4\\homefolders\\leerlingen\\" . $year);
}
//solideagle\plugins\ad\manageuser::setHomeFolder($)

?>
