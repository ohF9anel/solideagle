<?php

require_once '../data_access/Task.php';

use DataAccess\Task;

$task = new Task();

$task->setName("taakske");
$task->setPathScript("link naar script");
$task->setTaskTypeId("1");

$id = Task::addTask($task);

echo $id;
echo "finish";

Task::delTaskById(5);

?>
