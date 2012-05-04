<?php

use solideagle\data_access\PlatformGA;

use solideagle\scripts\GlobalUserManager;

use solideagle\data_access\PlatformSS;

use solideagle\data_access\Group;

use solideagle\data_access\PlatformAD;

use solideagle\scripts\smartschool\usermanager;

use solideagle\Config;


use solideagle\data_access\Type;
use solideagle\data_access\TaskTemplate;

use solideagle\scripts\ad\homefoldermanager;

use solideagle\data_access\Person;

use solideagle\logging\Logger;


class UsertasksController extends Zend_Controller_Action
{

	public function init()
	{

	}

	public function indexAction()
	{

	}

	/**
	 *
	 * @param Person $user
	 */
	private function doTasksForUser($user,$configstdclass)
	{
		//check if user has password
		if((strlen($user->getAccountPassword()) >= 8) && (
				$configstdclass->createAdAccount || $configstdclass->createSsAccount || $configstdclass->createGappAccount))
		{
			GlobalUserManager::createAccounts($user, $configstdclass);
		}else{
			Logger::log("No account created for user: " . $user->getAccountUsername() . " because he does not have a password.",PEAR_LOG_ALERT);
		}
		 
		if($configstdclass->deleteAdAccount || $configstdclass->deleteSsAccount || $configstdclass->deleteGappAccount)
		{
			GlobalUserManager::deleteAccounts($user,$configstdclass);
		}
		 
		if($configstdclass->enableAdAccount || $configstdclass->enableSsAccount || $configstdclass->enableGappAccount)
		{
			GlobalUserManager::enableDisableAccounts($user,$configstdclass);
		}
		 
		if($configstdclass->disableAdAccount || $configstdclass->disableSsAccount || $configstdclass->disableGappAccount)
		{
			GlobalUserManager::enableDisableAccounts($user,$configstdclass);
		}
		 
		 
		if($configstdclass->createAdHomedir)
		{
			$server = $configstdclass->homefolderServer;
			$homeFolderPath = $configstdclass->homefolderPath;
			$scanSharePath  = $configstdclass->scanSharePath;
			$wwwSharePath = $configstdclass->wwwSharePath;
			 
			//up & down folders
			$downloadSharePath = NULL;
			$uploadSharePath = NULL;
			if($configstdclass->createUpDownFolders)
			{
				$downloadSharePath = $configstdclass->downloadSharePath;
				$uploadSharePath = $configstdclass->uploadSharePath;
			}
			
			solideagle\scripts\ad\homefoldermanager::prepareAddHomefolder($server, $homeFolderPath, $scanSharePath,
					$wwwSharePath, $user,$uploadSharePath,$downloadSharePath);
		}
		 
	}
	 
	public function posttaskAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		$configstdclass = new stdClass();

		$configstdclass->createAdAccount = $this->getRequest()->getPost('createAdAccount',false);
		$configstdclass->deleteAdAccount = $this->getRequest()->getPost('deleteAdAccount',false);
		$configstdclass->disableAdAccount = $this->getRequest()->getPost('disableAdAccount',false);
		$configstdclass->enableAdAccount = $this->getRequest()->getPost('enableAdAccount',false);
		$configstdclass->createAdHomedir = $this->getRequest()->getPost('createAdHomedir',false);
		$configstdclass->homefolderServer = $this->getRequest()->getPost('homefolderServer');
		$configstdclass->homefolderPath = $this->getRequest()->getPost('homefolderPath');
		$configstdclass->scanSharePath = $this->getRequest()->getPost('scanSharePath');
		$configstdclass->wwwSharePath = $this->getRequest()->getPost('wwwSharePath');
		$configstdclass->createUpDownFolders = $this->getRequest()->getPost('createUpDownFolders',false);
		$configstdclass->uploadSharePath = $this->getRequest()->getPost('uploadSharePath');
		$configstdclass->downloadSharePath = $this->getRequest()->getPost('downloadSharePath');
		$configstdclass->moveAdHomedir = $this->getRequest()->getPost('moveAdHomedir',false);
		$configstdclass->createSsAccount = $this->getRequest()->getPost('createSsAccount',false);
		$configstdclass->deleteSsAccount = $this->getRequest()->getPost('deleteSsAccount',false);
		$configstdclass->disableSsAccount = $this->getRequest()->getPost('disableSsAccount',false);
		$configstdclass->enableSsAccount = $this->getRequest()->getPost('enableSsAccount',false);
		$configstdclass->createGappAccount = $this->getRequest()->getPost('createGappAccount',false);
		$configstdclass->deleteGappAccount = $this->getRequest()->getPost('deleteGappAccount',false);
		$configstdclass->disableGappAccount = $this->getRequest()->getPost('disableGappAccount',false);
		$configstdclass->enableGappAccount = $this->getRequest()->getPost('enableGappAccount',false);

		if($this->getRequest()->getPost('submitBtn') == "addTasks")
		{
			if(count($this->getRequest()->getPost('users',array())) <= 0)
			{
				echo "Geen gebruikers geselecteerd!";
				return;
			}
				
			foreach(Person::getPersonsByIds($this->getRequest()->getPost('users')) as $user)
			{
				$this->doTasksForUser($user,$configstdclass);
			}
		}
		else if($this->getRequest()->getPost('submitBtn') == "addTemplate" || $this->getRequest()->getPost('submitBtn') == "editTemplate")
		{
			$taskTemplate = new TaskTemplate();
			$taskTemplate->setTemplateName($this->getRequest()->getPost('txtName'));
				
			$taskTemplate->setTemplateConfig($configstdclass);
				
			if($this->getRequest()->getPost('submitBtn') == "editTemplate")
			{
				//bit unorthodox but will have to do for now
				TaskTemplate::delTaskTemplateByName($this->getRequest()->getPost('txtName'));
			}
				
			TaskTemplate::addTaskTemplate($taskTemplate);
		}

		return;
	}

	public function showtaskAction()
	{
		$this->_helper->layout()->disableLayout();

		$this->view->defaults = new stdClass();

		$this->view->defaults->homefolderServer = Config::singleton()->ssh_server;
		$this->view->defaults->homefolderPath = Config::singleton()->path_homefolders;
		$this->view->defaults->scanSharePath = Config::singleton()->path_share_scans;
		$this->view->defaults->wwwSharePath = Config::singleton()->path_share_www;
		$this->view->defaults->downloadSharePath =   Config::singleton()->path_share_downloads;
		$this->view->defaults->uploadSharePath =  Config::singleton()->path_share_uploads;
		$this->view->defaults->createAdAccount = false;
		$this->view->defaults->deleteAdAccount = false;
		$this->view->defaults->disableAdAccount = false;
		$this->view->defaults->enableAdAccount = false;
		$this->view->defaults->createAdHomedir = false;
		$this->view->defaults->createUpDownFolders = false;
		$this->view->defaults->moveAdHomedir = false;
		$this->view->defaults->createSsAccount = false;
		$this->view->defaults->deleteSsAccount = false;
		$this->view->defaults->disableSsAccount = false;
		$this->view->defaults->enableSsAccount = false;
		$this->view->defaults->createGappAccount = false;
		$this->view->defaults->deleteGappAccount = false;
		$this->view->defaults->disableGappAccount = false;
		$this->view->defaults->enableGappAccount = false;



		//get users from post
		$usersArr = $this->getRequest()->getPost('selectedUsers',array());

		//no users given in post, try other options
		if(count($usersArr) < 1)
		{
			$usersArr = Person::getPersonIdsByGroupId($this->getRequest()->getPost('selectedGroup'));
		}


		$this->view->users = json_encode($usersArr);

		//get template name
		$templatename = $this->getRequest()->getPost('templatename',NULL);
		$this->view->templatename = $templatename;

		//create task from template
		$tasksFromTemplate =  $this->getRequest()->getPost('fromTemplate',false);

		//are we called from template edit link?
		$edittemplate = $this->getRequest()->getPost('editTemplate',false);

		$addtemplate = $this->getRequest()->getParam('addtemplate',false);

		//init
		$this->view->manageTemplate = 0;
		$this->view->addtemplate = 0;
		$this->view->hasAdAccount = 0;
		$this->view->hasSsAccount = 0;
		$this->view->hasGappAccount = 0;

		if($templatename != null)
		{
			$this->view->defaults = TaskTemplate::getTemplateByName($templatename)->getTemplateConfig();
				
				
		}



		if ($edittemplate)
		{
			$this->view->manageTemplate = true;
		}
		else if($addtemplate)
		{
			$this->view->addtemplate = true;
			$this->view->manageTemplate = true;
		}
		else if((count($usersArr) > 1) || $tasksFromTemplate)
		{
			$this->view->multipleAccountsSelected = true;
			$this->view->hasAdAccount = true;
			$this->view->hasSsAccount = true;
			$this->view->hasGappAccount = true;
		}
		// single user selected
		else if(count($usersArr) < 2 && count($usersArr) > 0)
		{
			$this->view->multipleAccountsSelected = false;

			$userId = $usersArr[0];
			$platformAd = PlatformAD::getPlatformConfigByPersonId($userId);
			$platformSs = PlatformSS::getPlatformConfigByPersonId($userId);
			$platformGa = PlatformGA::getPlatformConfigByPersonId($userId);

			// ad?
			if ($platformAd != null)
			{
				$this->view->hasAdAccount = true;
				$this->view->hasAdAccountEnabled = $platformAd->getEnabled();
			}
			else
				$this->view->hasAdAccount = false;

			// smartschool?
			if ($platformSs != null)
			{
				$this->view->hasSsAccount = true;
				$this->view->hasSsAccountEnabled = $platformSs->getEnabled();
			}
			else
				$this->view->hasSSAccount = false;

			// gapps?
			if ($platformGa != null)
			{
				$this->view->hasGappAccount = true;
				$this->view->hasGappAccountEnabled = $platformGa->getEnabled();
			}
			else
				$this->view->hasSSAccount = false;
			// multiple accounts selected
		}


	}

	public function managetasktemplatesAction()
	{
		$this->_helper->layout()->disableLayout();
		$users = $this->getRequest()->getParam('selectedUsers',array());

		//no users given try to get the from the group
		if(count($users) < 1)
		{
			$users = Person::getPersonIdsByGroupId($this->getRequest()->getParam('selectedGroup'));
		}

		$this->view->users = json_encode($users);

	}

	public function removetasktemplateAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		if(($taskname = $this->getRequest()->getPost("templatename",false)))
		{
			TaskTemplate::delTaskTemplateByName($taskname);
		}
	}

	public function gettemplatesAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		 
		echo $this->templatesToJson(TaskTemplate::getAllTemplates());
		return;
	}

	private function templatesToJson($templates)
	{
		$finalarr = array();
		foreach($templates as $template)
		{
			$finalarr[] = $template->getTemplateName();
		}

		return json_encode($finalarr);
	}

	 

	//placeholder, do not use!
	/*private function doUserThings()
	 {
	die("placeholder, do not use!");
	//placeholder, do not use!
	return;
	return; //placeholder, do not use!
	return; //placeholder, do not use!
	//placeholder, do not use!
	 
	if($this->getRequest()->getPost('createAdHomedir',false))
	{
	 
	$server = $this->getRequest()->getPost("HomefolderServer",NULL);
	$homeFolderPath = $this->getRequest()->getPost("HomefolderPath",NULL);
	$scanSharePath  = $this->getRequest()->getPost("ScanSharePath",NULL);
	$wwwSharePath = $this->getRequest()->getPost("WWWSharePath",NULL);
	 
	//up & down folders
	$downloadSharePath = NULL;
	$uploadSharePath = NULL;
	if($this->getRequest()->getPost('createUpDownFolders',false))
	{
	$downloadSharePath = $this->getRequest()->getPost("DownloadSharePath",NULL);
	$uploadSharePath = $this->getRequest()->getPost("UploadSharePath",NULL);
	}
	 
	foreach($users as $user)
	{
	solideagle\scripts\ad\homefoldermanager::prepareAddHomefolder($server, $homeFolderPath, $scanSharePath,
			$wwwSharePath, $user,$uploadSharePath,$downloadSharePath);
	}
	}
	}*/


}



