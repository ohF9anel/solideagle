<?php


use solideagle\plugins\ad\sshpreformatter;

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
    	$batchfile = sshpreformatter::singleton()->getFileForServer("S1.solideagle.lok");
    	$batchfile->writeToFile("mkdir c:\\itworks\n");
    	
    	sshpreformatter::singleton()->runAllBatchfiles();
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





