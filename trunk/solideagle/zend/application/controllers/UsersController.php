<?php

require_once 'data_access/Type.php';
require_once 'data_access/Person.php';
require_once 'data_access/Task.php';
require_once 'data_access/PersonTaskQueue.php';
require_once 'data_access/Group.php';
require_once 'data_access/GroupTaskQueue.php';
use DataAccess\Person;
use DataAccess\Type;
use DataAccess\Task;
use DataAccess\PersonTaskQueue;
use DataAccess\Group;
use DataAcces\GroupTaskQueue;


function groupsToJson($roots,$isfirst = true)
{
	if(count($roots) === 0)
		return false;
	 
	$thisrootarr = array();
	 
	foreach($roots as $group)
	{
		$arr = array("data" => array("title" => $group->getName(), "attr" => array("href" => "javascript:void(0)")), "attr" => array("id" => "tree" . $group->getId(),"groupid" => $group->getId(),"groupname" => $group->getName()));
		
		if($isfirst)
		{
			$arr["state"] = "open";
			$isfirst = false;
		}
		
		if(($children = groupsToJson($group->getChildGroups(),$isfirst)) != false)
		{
			$arr["children"] = $children;
		}
		
		$thisrootarr[] = $arr;
	}

	return $thisrootarr;
	 
}

class UsersController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
    	$this->view->users = Person::getUsersForDisplayByGroup();
    	$this->view->groups = Group::getTree();
    }
    
    public function getgroupAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	
    	$arr = groupsToJson(Group::getTree());
    	
    	
    	
    	echo json_encode($arr);
    	
    
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
 		
 		if(count($errors = Person::validatePerson($person)) > 0)
 		{
 			echo json_encode($errors);
 			return;
 		}
 		
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
    
    
    public function getusersAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	
    	$persons = array();
    	
    	$data = $this->_request->getParams();
    	
    	
    	$gid = -1;
    	
    	if(isset($data["gid"]))
    	{
    		$gid = $data["gid"];
    	}
    	
    	foreach(Person::getUsersForDisplayByGroup($gid) as $gp)
    	{
    		$person[0] = $gp->getId();
    		$person[1] = $gp->getFirstName();
    		$person[2] = $gp->getName();
    		$person[3] = $gp->getAccountUserName();
    		$person[4] = $gp->getAccountActive();
    		$person[5] = $gp->getMadeOn();				
    		
    		$persons[] = $person;
    	}
    	
    	echo json_encode(array("aaData" => $persons));
    }

    public function addgroupAction()
    {
        $this->view->groups = Group::getTree();
     //   $this->view->tasks = 
       
        
     
        
    }


    public function addgrouppostAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
   
    	$data = $this->_request->getParams();
    	$group = new Group();
    	$group->setParentId($data["parentId"]);
    	$group->setName($data["txtName"]);
    	
    	
    	
    	if(count($errors = Group::validateGroup($group)) > 0)
    	{
    		echo "<pre>";
    		var_dump($errors);
    		echo "</pre>";
    		return;
    	}
    	
    	if(Group::getGroupById($group->getParentId()) === NULL)
    	{
    		echo "Parent bestaat niet!";
    		return;
    	}
    	
    	$groupid = Group::addGroup($group);
    	
    	$group->setId($groupid);
    	
    	$gtaskq = new GroupTaskQueue();
    	
    	$gtaskq->setGroup_id($groupid);
    	$gtaskq->setTask_id("27");
    	$gtaskq->setConfiguration(array("action" => "Add", "groupobj" => $group));
    	
    	GroupTaskQueue::addTaskToQueue($gtaskq);
    	
    	
    }
    
}





