<?php

namespace solideagle\scripts;

use solideagle\data_access\database\DatabaseCommand;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$cmd = new DatabaseCommand("truncate task_queue");
$cmd->execute();

?>