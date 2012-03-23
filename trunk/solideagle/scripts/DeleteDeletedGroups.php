<?php

use Database\DatabaseCommand;

require_once 'data_access/database/databasecommand.php';

$sql = "DELETE a FROM group_closure as a
JOIN `group` as g ON a.child_id = g.id WHERE g.deleted = 1;";

$cmd = new DatabaseCommand($sql);
$cmd->execute();

$sql = "DELETE FROM `CentralAccountDB`.`group`
WHERE deleted = 1;";

$cmd->newQuery($sql);

$cmd->execute();




?>