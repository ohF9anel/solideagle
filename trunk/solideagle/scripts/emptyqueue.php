<?php

use Database\DatabaseCommand;

require_once 'data_access/database/databasecommand.php';
$cmd = new DatabaseCommand("truncate task_queue");
$cmd->execute();

?>