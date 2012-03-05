<?php

require_once '../data_access/Process.php';
require_once '../data_access/Task.php';
require_once '../data_access/TaskType.php';

use DataAccess\Process;
use DataAccess\Task;
use DataAccess\TaskType;

$process = new Process();

$process->setName("proceske");

$task = new Task();

$task->setName("taakske");
$task->setPathScript("link naar script");
$task->setTaskType("taaktype");
$task->setId(Task::addTask($task));

$process->addTask($task);

$id = Process::addProcess($process);

echo $id;
echo "finish";


?>
