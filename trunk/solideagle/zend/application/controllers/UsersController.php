<?php

require_once 'data_access/Type.php';
require_once 'data_access/Person.php';
require_once 'data_access/Task.php';

require_once 'data_access/Group.php';

require_once "scripts/Usermanager.php";

use DataAccess\Person;
use DataAccess\Type;

use DataAccess\Group;

use scripts\Usermanager;



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
    

    
    

    public function adduserAction()
    {
    	$this->_helper->layout()->disableLayout();
    	
    	$data = $this->getRequest()->getParams();
    
   		 if(isset($data["gid"]))
		{
			$groupid = $data["gid"];
			$this->view->group = Group::getGroupById($groupid);
		}else{
			exit();
		}
    	
    }

    public function adduserpostAction()
    {
        $this->_helper->layout()->disableLayout();
 		$this->_helper->viewRenderer->setNoRender(true);
 		
 		$data = $this->getRequest()->getParams();
 		
 		$person = new Person();
 		
 		if(isset($data["type"]))
 		{
	 		foreach($data["type"] as $id)
	 		{
	 			$person->addType(new Type($id));
	 		}
 		}
 		
 		if(isset($data["txtFirstName"]) && isset($data["txtName"]) && isset($data["groupid"]))
 		{
 		
 		$person->setFirstName($data["txtFirstName"]);
 		$person->setName($data["txtName"]);
 		$person->setGroupId($data["groupid"]);
 		}else{
 			echo "Alle velden moetten ingevuld zijn";
 			return;
 		}
 		
 		if(count($errors = Person::validatePerson($person)) > 0)
 		{
 			echo json_encode($errors);
 			return;
 		}
 		
 		$personid = Person::addPerson($person);
 		
 		$person->setId($personid);
 		
 		Usermanager::Add($person);
 	

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




   
    
}





