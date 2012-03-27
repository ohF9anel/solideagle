<?php

namespace solideagle\scripts;

use solideagle\data_access\database\DatabaseCommand;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$sql = "DELETE a FROM group_closure as a
JOIN `group` as g ON a.child_id = g.id WHERE g.deleted = 1;";

$cmd = new DatabaseCommand($sql);
$cmd->execute();

$sql = "DELETE a FROM `person` as a
JOIN `group` as g ON a.group_id = g.id WHERE g.deleted = 1;";

$cmd = new DatabaseCommand($sql);
$cmd->execute();


$sql = "DELETE FROM `CentralAccountDB`.`group`
WHERE deleted = 1;";

$cmd->newQuery($sql);

$cmd->execute();




?>