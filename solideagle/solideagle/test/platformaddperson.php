<?php

namespace solideagle\test;

use solideagle\data_access\platforms;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$platform = new platforms();

$platform->setPlatformType(platforms::PLATFORM_SMARTSCHOOL);
$platform->setPersonId(171);
$platform->setEnabled(true);

//echo platforms::addPlatform($platform);

var_dump(platforms::getPlatformsByPersonId(171));

?>
