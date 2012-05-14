<?php


use solideagle\plugins\smartschool\GroupsAndUsersCache;

use solideagle\utilities\XMLParser;

use solideagle\plugins\smartschool\data_access\Api;

use solideagle\data_access\Course;





class CourseController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	
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
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
 
    	
      	 var_dump( $this->getRequest()->getParams());
    }


}





