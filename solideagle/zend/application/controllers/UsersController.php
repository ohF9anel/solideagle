<?php


use solideagle\scripts\GlobalUserManager;

use solideagle\utilities\SuperEntities;

use solideagle\data_access\helpers\DateConverter;

use solideagle\data_access\Person;
use solideagle\data_access\PlatformAD;
use solideagle\data_access\PlatformGA;
use solideagle\data_access\PlatformSS;
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
		else //new user
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
		$person->setUniqueIdentifier($this->getRequest()->getPost('uniqueIdentifier'));
		$person->setGroupId($this->getRequest()->getPost('groupId'));
		$person->setInformatId($this->getRequest()->getPost('informatId'));
		$person->setPictureUrl($this->getRequest()->getPost('PictureUrl'));
			
		if(count($errors = Person::validatePerson($person)) > 0)
		{
			echo json_encode($errors);
			return;
		}
			
		if($postmode === "edit")
		{
			$person->setId($this->getRequest()->getPost('Id'));
			GlobalUserManager::updateUser($person);
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
		$this->_helper->layout()->disableLayout();
		$this->view->groups = Group::getAllGroups();

		$this->view->oldgid = $this->getRequest()->getParam("oldgid",false);
	}

	public function movepostAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
			
		$oldgid = $this->getRequest()->getPost("oldgid",false);
		$newgid = $this->getRequest()->getParam("newgid",false);

		if($oldgid ===false || $newgid ===false)
		{
			echo "oldgid or newgid not set!";
			return;
		}

		if($oldgid == $newgid)
		{
			echo "moving to same group, aborting";
			return;
		}

		$users = Person::getPersonsByIds($this->getRequest()->getParam("users",array()));

		foreach($users as $user)
		{
			GlobalUserManager::moveUser($user, $newgid, $oldgid);
		}

	}


	public function removeAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$pid = $this->getRequest()->getPost('jspostArr');

		$person = Person::getPersonById($pid);

		if ($person != null)
		{
			if(PlatformAD::getPlatformConfigByPersonId($pid) !== NULL)
			{
				\solideagle\scripts\ad\usermanager::prepareDelUser($person);
				PlatformAD::removePlatformByPersonId($pid);
			}

			if(PlatformSS::getPlatformConfigByPersonId($pid) !== NULL)
			{
				//                        \solideagle\scripts\smartschool\usermanager::prepareDelUser($person);
				//                        PlatformSS::removePlatformByPersonId($pid);
			}

			if(PlatformGA::getPlatformConfigByPersonId($pid) !== NULL)
			{
				\solideagle\scripts\ga\usermanager::prepareDelUser($person);
				PlatformGA::removePlatformByPersonId($pid);
			}

			Person::delPersonById($pid);
		}

	}

}
