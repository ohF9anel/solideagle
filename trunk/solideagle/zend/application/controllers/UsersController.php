<?php


use solideagle\data_access\helpers\DateConverter;

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
		
		echo $state;
		
		if($state === "show" && isset($data["pid"]) && is_numeric($data["pid"]))
		{
			$this->view->state = 2;
			$this->view->person = Person::getPersonById($data["pid"]);
			if($this->view->person === NULL)
			{
				exit("user does not exist");
			}
			$this->view->group = Group::getGroupById($this->view->person->getGroupId());
		}
		else if($state === "edit" && isset($data["pid"]) && is_numeric($data["pid"]))
		{
			$this->view->state = 1;
			$this->view->person = Person::getPersonById($data["pid"]);
			if($this->view->person === NULL)
			{
				exit("user does not exist");
			}
			$this->view->group = Group::getGroupById($this->view->person->getGroupId());
		}
		else
		{
			$this->view->state = 0;
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
		$person->setAccountActive($this->getRequest()->getPost('AccountActive'));
		$person->setAccountActiveFrom(DateConverter::DisplayDateTodbDate($this->getRequest()->getPost('AccountActiveFrom')));
		$person->setAccountActiveUntill(DateConverter::DisplayDateTodbDate($this->getRequest()->getPost('AccountActiveUntill')));
		$person->setOtherInformation($this->getRequest()->getPost('OtherInformation'));
			
		if(count($errors = Person::validatePerson($person)) > 0)
		{
			echo json_encode($errors);
			return;
		}
			
		Person::addPerson($person);

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
			$person[1] = $gp->getFirstName();
			$person[2] = $gp->getName();
			$person[3] = $gp->getAccountUserName();
			$person[4] = $gp->getAccountActive();
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


}







