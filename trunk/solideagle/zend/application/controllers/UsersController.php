<?php


use solideagle\utilities\SuperEntities;

use solideagle\data_access\helpers\DateConverter;

use solideagle\data_access\Person;
use solideagle\data_access\platforms;
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
    }

    public function userformAction()
    {
		$this->_helper->layout()->disableLayout();
		 
		$data = $this->getRequest()->getParams();
		 
		$this->view->stateNew = 0;
		$this->view->stateEdit = 1;
		$this->view->stateShow = 2;

		if(!isset($data["state"]))
		{
			exit("no state requested");
		}
		$this->view->types = Type::getAll();
			
		
		$state = $data["state"];

		
		if($state === "show" && isset($data["pid"]) && is_numeric($data["pid"]))
		{
			$this->view->state = $this->view->stateShow;
			$this->view->person = Person::getPersonById($data["pid"]);
			if($this->view->person === NULL)
			{
				exit("user does not exist");
			}
			$this->view->group = Group::getGroupById($this->view->person->getGroupId());
		}
		else if($state === "edit" && isset($data["pid"]) && is_numeric($data["pid"]))
		{
			$this->view->state = $this->view->stateEdit;
			$this->view->person = Person::getPersonById($data["pid"]);
			if($this->view->person === NULL)
			{
				exit("user does not exist");
			}
			$this->view->group = Group::getGroupById($this->view->person->getGroupId());
		}
		else
		{
			$this->view->state = $this->view->stateNew;
			$this->view->person = new Person(); //We don't want errors when rendering the form
			if(isset($data["gid"]) && is_numeric($data["gid"]))
			{
				$this->view->group = Group::getGroupById($data["gid"]);
				if($this->view->group === NULL)
				{
					exit("group does not exist");
				}
			}else{
				exit("invalid parameters");
			}
		}
		
    }

    public function adduserpostAction()
    {
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
				
		$person = new Person();
		
		
		$postmode = $this->getRequest()->getPost('submit');
	
		
		if($postmode === "generateUsername")
		{
			$isStudent = true;
			foreach($this->getRequest()->getPost('ptype', array()) as $id)
			{
				if($id == 2)
				{
					$isStudent = false;
				}
					
			}
			$person->setFirstName($this->getRequest()->getPost('FirstName'));
			$person->setName($this->getRequest()->getPost('Name'));
			
			echo "GeneratedUsername:" . Person::generateUsername($person,$isStudent);
			return;
		}
		else if($postmode === "generatePassword")
		{
			echo "GeneratedPassword:" . Person::generatePassword();
			return;
		}
		
		//it's a new user or edit
		
		foreach($this->getRequest()->getPost('ptype', array()) as $id)
		{
			$person->addType(new Type($id));
		}
		
		$person->setFirstName($this->getRequest()->getPost('FirstName'));
		$person->setName($this->getRequest()->getPost('Name'));
		$person->setGroupId($this->getRequest()->getPost('GroupId'));
		$person->setGender($this->getRequest()->getPost('Gender'));
		$person->setBirthDate(DateConverter::DisplayDateTodbDate($this->getRequest()->getPost('BirthDate')));
		$person->setEmail($this->getRequest()->getPost('Email'));
		$person->setPhone($this->getRequest()->getPost('Phone'));
		$person->setAccountUsername($this->getRequest()->getPost('AccountUsername'));
		$person->setAccountPassword($this->getRequest()->getPost('AccountPassword'));
		//$person->setAccountActive($this->getRequest()->getPost('AccountActive'));
		$person->setAccountActiveFrom(DateConverter::DisplayDateTodbDate($this->getRequest()->getPost('AccountActiveFrom')));
		$person->setAccountActiveUntill(DateConverter::DisplayDateTodbDate($this->getRequest()->getPost('AccountActiveUntill')));
		$person->setOtherInformation($this->getRequest()->getPost('OtherInformation'));
			
		if(count($errors = Person::validatePerson($person)) > 0)
		{
			echo json_encode($errors);
			return;
		}
			
	
		if($postmode === "edit")
		{
			$person->setId($this->getRequest()->getPost('Id'));
                        $oldPerson = Person::getPersonById($person->getId());
			Person::updatePerson($person);
                        $person->setGroupId($oldPerson->getGroupId());
                        if (platforms::getPlatformAdByPersonId($person->getId()) != null);
                            solideagle\scripts\ad\usermanager::prepareUpdateUser($person);
                        if (platforms::getPlatformGappByPersonId($person->getId()) != null)
                            solideagle\scripts\ga\usermanager::prepareUpdateUser($person, $oldPerson->getAccountUsername());
//                        if (platforms::getPlatformSmartschoolByPersonIdByPersonId($person->getId()) != null)
//                            solideagle\scripts\smartschool\usermanager::prepareUpdateUser($person);
                        
		}else{
			Person::addPerson($person);
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
		}else{
			return;
		}
		 
		/*
		 * Fields for jquery datatable
		*
		*/
		 
		foreach(Person::getUsersForDisplayByGroup($gid) as $gp)
		{
			$person[0] = $gp->getId();
			$person[1] = $gp->getId();
			$person[2] = SuperEntities::encode($gp->getFirstName());
			$person[3] = SuperEntities::encode($gp->getName());
			$person[4] = SuperEntities::encode($gp->getAccountUserName());
			$person[5] = DateConverter::longDbDateToDisplayDate($gp->getMadeOn());
			 
			$persons[] = $person;
		}
		//must be called aaData, see datatables ajax docs
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

    public function showexterndetailsAction()
    {
        // action body
    }

    public function moveAction()
    {
        $this->view->groups = Group::getAllGroups();
        
        //oldgid
        //persid
    }
    
    public function movepostAction()
    {
    	$this->_helper->layout()->disableLayout();
    	$this->_helper->viewRenderer->setNoRender(true);
    	

    	$oldgid =  $this->getRequest()->getParam("oldgid",false);
    	$pid =  $this->getRequest()->getParam("personid",false);
    	
    	//params set, and not moving to group where person is already?
    	if(($newgid = $this->getRequest()->getParam("newgroupid",false)) && $newgid != $oldgid && $oldgid !== false && $pid !== false)
    	{
    		moveAUser($pid,$newgid);
    	}
    	
    	return;
    }
    
    private function moveAUser($pid,$newgid)
    {
    	//all good, move
    	
    	$person = Person::getPersonById($pid);
    	
    	$person->setGroupId($newgid);
    	
    	Person::updatePerson($person);
    	
    	if(platforms::getPlatformAdByPersonId($person->getId()) !== NULL)
    	{
    		\solideagle\scripts\ad\usermanager::prepareUpdateUser($person);
    	}
    	
    	if(platforms::getPlatformGappByPersonId($person->getId()) !== NULL)
    	{
    		\solideagle\scripts\ga\usermanager::prepareUpdateUser($person);
    	}
    	
    	if(platforms::getPlatformSmartschoolByPersonId($person->getId()) !== NULL)
    	{
    		//\solideagle\scripts\smartschool\usermanager::
    	}
    }


}











