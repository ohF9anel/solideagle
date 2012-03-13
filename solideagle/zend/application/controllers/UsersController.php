<?php

require_once 'data_access/Type.php';
require_once 'data_access/Person.php';
require_once 'data_access/Task.php';
use DataAccess\Person;
use DataAccess\Type;
use DataAccess\Task;

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	//$this->view->users = Person::getAll();
    }
    
    public function adduserAction()
    {
    	
    	$this->view->types = Type::getAll();
    	$this->view->tasks = Task::getAllByType("user");
    }

    public function adduserpostAction()
    {
        $this->_helper->layout()->disableLayout();
 		$this->_helper->viewRenderer->setNoRender(true);
 		
 		$data = $this->_request->getParams();
 		
 		$person = new Person();
 		
 		if(isset($data["type"]))
 		{
	 		foreach($data["type"] as $id)
	 		{
	 			$person->addType(new Type($id));
	 		}
 		}
 		
 		$person->setFirstName($data["txtFirstName"]);
 		$person->setName($data["txtName"]);
 		if(isset($data["task"]))
 		{
 			foreach($data["task"] as $task)
 			{
 				
 			}
 		}
 		var_dump($person);
 		
 	//	Person::addPerson($person);
 		
 		
    }


}



