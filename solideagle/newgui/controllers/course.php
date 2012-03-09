<?php

require_once 'data_access/Course.php';
require_once 'basecontroller.php';
use DataAccess\Course;

basecontroller::load(new CourseController());


class CourseController
{
	public function getCourseList()
	{
		
		$out;
		
		foreach(Course::getAllCourses() as $course)
		{
			$out[] = array("id" => $course->getId(),"name" => $course->getName());
			
		}
		
		echo json_encode($out);

	}
	
	public function addNew($params)
	{
		
		
		
			$course = new Course();
			$course->setName($params["coursename"]);
			
			$errors = Course::validateCourse($course);
			
			if(!empty($errors))
			{
				echo "Fout bij opslaan:\n";
				foreach ($errors as $error)
				{
					echo $error . "\n";
				}
			}else{
				
				Course::addCourse($course);
	
			}
			
			
		
	}
	
	public function delete($params)
	{
		Course::delCourseById($params["id"]);
	}
}



?>
