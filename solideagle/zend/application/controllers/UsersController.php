<?php

require_once 'data_access/Type.php';
require_once 'data_access/Person.php';
require_once 'data_access/Task.php';
require_once 'data_access/PersonTaskQueue.php';
use DataAccess\Person;
use DataAccess\Type;
use DataAccess\Task;
use DataAccess\PersonTaskQueue;

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$this->view->users = Person::getUsersForDisplayByGroup();
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
 		
 		$personid = Person::addPerson($person);
 		
 		if(isset($data["task"]))
 		{
 			foreach($data["task"] as $task)
 			{
 				$tq = new PersonTaskQueue();
 				$tq->setPerson_id($personid);
 				$tq->setTask_id($task);
 				$tq->setConfiguration("No conf");
 				PersonTaskQueue::addTaskToQueue($tq);
 			}
 		}
 		
 		
 	
 		
 		
    }


}



