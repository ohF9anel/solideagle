<?php


use solideagle\data_access\Person;
use solideagle\data_access\Type;
use solideagle\data_access\Group;
use solideagle\scripts\Usermanager;


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
 			echo "Alle velden moeten ingevuld zijn";
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
    	}else{
    		return;
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

    public function showdetailsAction()
    {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
    	
		
		$data = $this->_request->getParams();
		
		if(isset($data["pid"]))
		{
			$pid = $data["pid"];
		}else{
			return;
		}
		

		$person	= Person::getPersonById($pid);
		
		if($person === NULL)
			return;
		
		echo $person->getJson();
    }


}







