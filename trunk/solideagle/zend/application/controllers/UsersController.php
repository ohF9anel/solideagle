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

		if(($state = $this->getRequest()->getParam("state")) === NULL)
		{
			exit("no state requested");
		}

		$this->view->types = Type::getAll();
			

		if($state === "show")
		{
			$this->view->state = $this->view->stateShow;
			$this->view->person = Person::getPersonById($this->getRequest()->getParam("pid"));
			if($this->view->person === NULL)
			{
				exit("user does not exist");
			}
			$this->view->group = Group::getGroupById($this->view->person->getGroupId());
		}
		else if($state === "edit")
		{
			$this->view->state = $this->view->stateEdit;
			$this->view->person = Person::getPersonById($this->getRequest()->getParam("pid"));
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

			$this->view->group = Group::getGroupById($this->getRequest()->getParam("gid"));
			if($this->view->group === NULL)
			{
				exit("group does not exist");
			}

		}

	}

	public function adduserpostAction()
	{
		$this->_helper->layout()->disableLayout();

		$this->_helper->viewRenderer->setNoRender(true);

		$person = NULL;

		$postmode = $this->getRequest()->getPost('submit');

		if($postmode === "edit")
		{
			$person = Person::getPersonById($this->getRequest()->getPost('Id'));
			$person->resetTypes(); //important for edit
		}else{
			$person = new Person();
		}



		foreach($this->getRequest()->getPost('ptype', array()) as $id)
		{
			$person->addType(new Type($id));
		}


		if($postmode === "generateUsername")
		{
			$isStudent = false;

			if($person->isTypeOf(Type::TYPE_LEERLING))
			{
				$isStudent = true;
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

		$person->setFirstName($this->getRequest()->getPost('FirstName'));
		$person->setName($this->getRequest()->getPost('Name'));
		$person->setGender($this->getRequest()->getPost('Gender'));
		$person->setBirthDate(DateConverter::DisplayDateTodbDate($this->getRequest()->getPost('BirthDate')));
		$person->setEmail($this->getRequest()->getPost('Email'));
		$person->setPhone($this->getRequest()->getPost('Phone'));
		$person->setAccountUsername($this->getRequest()->getPost('AccountUsername'));
		$person->setAccountPassword($this->getRequest()->getPost('AccountPassword'));
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

		$gid = $this->getRequest()->getParam("gid");

			
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
			$person[6] = DateConverter::longDbDateToDisplayDate($gp->getMadeOn());
				
			$platformstatus = array();
				
			if(($status = PlatformSS::getPlatformConfigByPersonId($gp->getId())) !== NULL)
			{
				$platformstatus["SS"] = $status->getEnabled();
			}
			
			if(($status = PlatformGA::getPlatformConfigByPersonId($gp->getId())) !== NULL)
			{
				$platformstatus["GA"] = $status->getEnabled();
			}
			
			if(($status = PlatformAD::getPlatformConfigByPersonId($gp->getId())) !== NULL)
			{
				$platformstatus["AD"] = $status->getEnabled();
			}

			$person[5] = $platformstatus;

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

		$pid = $this->getRequest()->getParam("pid");

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

		//get users from post
		$users = $this->getRequest()->getPost('selectedUsers',array());
			
		//no users given in post, try other options
		if(count($users) < 1)
		{
			$users = Person::getPersonIdsByGroupId($this->getRequest()->getPost('selectedGroup'));
		}
			
		$this->view->usersCount = count($users);
		$this->view->users = json_encode($users);
	}

	public function movepostAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
			
		$newgid = $this->getRequest()->getParam("newgid",false);

		if( $newgid ===false)
		{
			echo "newgid not set!";
			return;
		}

		$users = Person::getPersonsByIds($this->getRequest()->getParam("users",array()));

		foreach($users as $user)
		{
			$oldgid = $user->getGroupId();
				
			if($oldgid == $newgid) //do not move to same group
			{
				continue;
			}
				
			GlobalUserManager::moveUser($user, $newgid, $oldgid);
		}

	}

	public function removeAction()
	{
		$this->_helper->layout()->disableLayout();

		if($this->getRequest()->getPost("submit") === "remove")
		{
			$this->_helper->viewRenderer->setNoRender(true);
				
			$users = Person::getPersonsByIds($this->getRequest()->getParam("users",array()));

			foreach($users as $user)
			{
				GlobalUserManager::deleteUser($user);
			}
		}
		else
		{
			//get users from post
			$users = $this->getRequest()->getPost('selectedUsers',array());

			//no users given in post, try other options
			if(count($users) < 1)
			{
				$users = Person::getPersonIdsByGroupId($this->getRequest()->getPost('selectedGroup'));
			}

			$this->view->usersCount = count($users);
			$this->view->users = json_encode($users);
		}
	}

	public function resetpwAction()
	{
		$this->_helper->layout()->disableLayout();

		if($this->getRequest()->getPost("submit") === "reset")
		{
			$this->_helper->viewRenderer->setNoRender(true);
				
			$users = Person::getPersonsByIds($this->getRequest()->getParam("users",array()));

			$randomPass = $this->getRequest()->getParam("random",false);

			foreach($users as $person)
			{
				//predefined or random
				if($randomPass)
				{
					$person->setAccountPassword(Person::generatePassword());
				}else{
					$pass = $this->getRequest()->getParam("AccountPassword");
					$passRepeat = $this->getRequest()->getParam("AccountPasswordRepeat");
						
					if($pass == $passRepeat)
					{
						$person->setAccountPassword($pass);
					}else{
						echo "Wachtwoorden niet gelijk!";
						return;
					}
				}
				GlobalUserManager::resetUserPassword($person);
			}
		}else{
			//get users from post
			$users = $this->getRequest()->getPost('selectedUsers',array());

			//or maybe only 1 user
			if($this->getRequest()->getPost("pid") !== NULL)
				$users[] = $this->getRequest()->getPost("pid");

			//no users yet, try other options
			if(count($users) < 1)
			{
				$users = Person::getPersonIdsByGroupId($this->getRequest()->getPost('selectedGroup'));
			}

			$this->view->usersCount = count($users);
			$this->view->users = json_encode($users);
		}
	}

	public function searchAction()
	{
		$this->_helper->layout()->disableLayout();


		if($this->getRequest()->getParam("submit",false))
		{
			$this->_helper->viewRenderer->setNoRender(true);
			 
			$personsToEncode = array();
			 
			$firstname = "%" . $this->getRequest()->getParam("voornaam","") . "%";
			$lastname = "%" . $this->getRequest()->getParam("naam",""). "%";
			$username ="%" . $this->getRequest()->getParam("gebruikersnaam",""). "%";
			 
			foreach(Person::searchPerson($firstname,$lastname,$username) as $person)
			{
				$personsToEncode[] = $person->getJson();
			}
			 
			echo json_encode($personsToEncode);
		}
	}


}




