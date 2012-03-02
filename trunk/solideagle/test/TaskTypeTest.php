<?php

require_once '../data_access/TaskType.php';

use DataAccess\TaskType;

$taskType = new TaskType();

$taskType->setName("taaktype");

$id = TaskType::addTaskType($taskType);

echo $id;
echo "finish";
?>
