<?php

namespace solideagle\test\ad;

use solideagle\plugins\ad\ManageUser;
use solideagle\data_access\Person;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

ManageUser::delUser("lkrn12");

?>
