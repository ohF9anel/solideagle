<?php


// require_once 'data_access/database/databasecommand.php';

// use Database\DatabaseCommand;

// $dbcmd = new DatabaseCommand("SELECT version(),now(),schema();");

// $reader = $dbcmd->executeReader();

// $reader->readAll(function($arr){

// 	var_dump($arr);

// });

require_once 'data_access/Course.php';

use DataAccess\Course;

$course = new Course();


$course->setName("TestCourseTrans");

$id = Course::addCourse($course);
echo $id;
echo "finish";

?>