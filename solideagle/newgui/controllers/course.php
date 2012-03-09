<?php



require_once 'data_access/Course.php';
require_once 'basecontroller.php';
use DataAccess\Course;

basecontroller::load(new CourseController());


class CourseController
{
	public function getCourseList()
	{
		echo "<ul>";
		foreach(Course::getAllCourses() as $course)
		{
				
			echo "<li>" . $course->getName() . "</li>";
		}
		echo "</ul>";
	}
	
	public function test()
	{
		echo "hello";
	}
}



?>



