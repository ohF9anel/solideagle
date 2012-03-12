<?php

require_once 'data_access/Course.php';
use DataAccess\Course;

class CourseController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function getallAction()
    {
        $this->_helper->layout()->disableLayout();
 		$this->_helper->viewRenderer->setNoRender(true);
 		
 		$out;
		
		foreach(Course::getAllCourses() as $course)
		{
			$out[] = array("id" => $course->getId(),"name" => $course->getName());
			
		}
		
		echo json_encode($out);
 		
    }

    public function testAction()
    {
       echo "ok";
    }


}





