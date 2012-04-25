<?php

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
		/* Initialize action controller here */
	}

	public function indexAction()
	{
			

	}

	public function managetasktemplatesAction()
	{
			

	}


	public function posttasksAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);

		if($this->getRequest()->getPost('submitBtn') == "addTasks")
		{

			if(count($this->getRequest()->getPost('users',array())) <= 0)
			{
				echo "Geen gebruikers geselecteerd!";
				return;
			}

			$users = array();

			foreach($this->getRequest()->getPost('users') as $userid)
			{
				$users[] = Person::getPersonById($userid);
			}

			/**
			 * ACTIVE DIRECTORY
			 */

			if($this->getRequest()->getPost('createAdAccount',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ad\usermanager::prepareAddUser($user);
				}
			}

			if($this->getRequest()->getPost('deleteAdAccountSure',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ad\usermanager::prepareDelUser($user);
				}
			}

			// disable account?
			if($this->getRequest()->getPost('blnAdDisable',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ad\usermanager::prepareUpdateUser($user, false);
				}
			}

			// enabled account?
			if($this->getRequest()->getPost('blnAdEnable',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ad\usermanager::prepareUpdateUser($user, true);
				}
			}

			/**
			 * SMARTSCHOOL
			 */

			// create account
			if($this->getRequest()->getPost('createSSAccount',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\smartschool\usermanager::prepareAddUser($user);
				}
			}

			/**
			 * GOOGLE APPS
			 */

			// create account
			if($this->getRequest()->getPost('createGappAccount',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ga\usermanager::prepareAddUser($user);
					solideagle\scripts\ga\usermanager::prepareAddUserToOu($user);
				}
			}

			// remove account?
			if($this->getRequest()->getPost('deleteGappAccountSure',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ga\usermanager::prepareDelUser($user);
				}
			}

			// disable account?
			if($this->getRequest()->getPost('blnGappDisable',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ga\usermanager::prepareUpdateUser($user, $user->getAccountUsername(), false);
				}
			}

			// enable account?
			if($this->getRequest()->getPost('blnGappEnable',false))
			{
				foreach($users as $user)
				{
					solideagle\scripts\ga\usermanager::prepareUpdateUser($user, $user->getAccountUsername(), true);
				}
			}

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
		}
		else if($this->getRequest()->getPost('submitBtn') == "addTemplate")
		{
			$taskTemplate = new TaskTemplate();
			$taskTemplate->setTemplateName($this->getRequest()->getPost('txtName'));

			$templateConfigArr['createAdAccount'] = $this->getRequest()->getPost('createAdAccount');
			$templateConfigArr['deleteAdAccount'] = $this->getRequest()->getPost('deleteAdAccount');
			$templateConfigArr['blnAdDisable'] = $this->getRequest()->getPost('blnAdDisable');
			$templateConfigArr['blnAdEnable'] = $this->getRequest()->getPost('blnAdEnable');
			$templateConfigArr['createAdHomedir'] = $this->getRequest()->getPost('createAdHomedir');
			$templateConfigArr['HomefolderServer'] = $this->getRequest()->getPost('HomefolderServer');
			$templateConfigArr['HomefolderPath'] = $this->getRequest()->getPost('HomefolderPath');
			$templateConfigArr['ScanSharePath'] = $this->getRequest()->getPost('ScanSharePath');
			$templateConfigArr['WWWSharePath'] = $this->getRequest()->getPost('WWWSharePath');
			$templateConfigArr['createUpDownFolders'] = $this->getRequest()->getPost('createUpDownFolders');
			$templateConfigArr['UploadSharePath'] = $this->getRequest()->getPost('UploadSharePath');
			$templateConfigArr['DownloadSharePath'] = $this->getRequest()->getPost('DownloadSharePath');
			$templateConfigArr['blnMoveHomeDir'] = $this->getRequest()->getPost('blnMoveHomeDir');
			$templateConfigArr['createSsAccount'] = $this->getRequest()->getPost('createSsAccount');
			$templateConfigArr['deleteSsAccount'] = $this->getRequest()->getPost('deleteSsAccount');
			$templateConfigArr['blnSsDisable'] = $this->getRequest()->getPost('blnSsDisable');
			$templateConfigArr['blnSsEnable'] = $this->getRequest()->getPost('blnSsEnable');
			$templateConfigArr['createGappAccount'] = $this->getRequest()->getPost('createGappAccount');
			$templateConfigArr['deleteGappAccount'] = $this->getRequest()->getPost('deleteGappAccount');
			$templateConfigArr['blnGappDisable'] = $this->getRequest()->getPost('blnGappDisable');
			$templateConfigArr['blnGappEnable'] = $this->getRequest()->getPost('blnGappEnable');

			$taskTemplate->setTemplateConfig($templateConfigArr);
			TaskTemplate::addTaskTemplate($taskTemplate);
		}
		else if($this->getRequest()->getPost('submitBtn') == "editTemplate")
		{
			$taskTemplate = new TaskTemplate();
			$taskTemplate->setTemplateName($this->getRequest()->getPost('txtName'));

			$templateConfigArr['createAdAccount'] = $this->getRequest()->getPost('createAdAccount');
			$templateConfigArr['deleteAdAccount'] = $this->getRequest()->getPost('deleteAdAccount');
			$templateConfigArr['blnAdDisable'] = $this->getRequest()->getPost('blnAdDisable');
			$templateConfigArr['blnAdEnable'] = $this->getRequest()->getPost('blnAdEnable');
			$templateConfigArr['createAdHomedir'] = $this->getRequest()->getPost('createAdHomedir');
			$templateConfigArr['HomefolderServer'] = $this->getRequest()->getPost('HomefolderServer');
			$templateConfigArr['HomefolderPath'] = $this->getRequest()->getPost('HomefolderPath');
			$templateConfigArr['ScanSharePath'] = $this->getRequest()->getPost('ScanSharePath');
			$templateConfigArr['WWWSharePath'] = $this->getRequest()->getPost('WWWSharePath');
			$templateConfigArr['createUpDownFolders'] = $this->getRequest()->getPost('createUpDownFolders');
			$templateConfigArr['UploadSharePath'] = $this->getRequest()->getPost('UploadSharePath');
			$templateConfigArr['DownloadSharePath'] = $this->getRequest()->getPost('DownloadSharePath');
			$templateConfigArr['blnMoveHomeDir'] = $this->getRequest()->getPost('blnMoveHomeDir');
			$templateConfigArr['createSsAccount'] = $this->getRequest()->getPost('createSsAccount');
			$templateConfigArr['deleteSsAccount'] = $this->getRequest()->getPost('deleteSsAccount');
			$templateConfigArr['blnSsDisable'] = $this->getRequest()->getPost('blnSsDisable');
			$templateConfigArr['blnSsEnable'] = $this->getRequest()->getPost('blnSsEnable');
			$templateConfigArr['createGappAccount'] = $this->getRequest()->getPost('createGappAccount');
			$templateConfigArr['deleteGappAccount'] = $this->getRequest()->getPost('deleteGappAccount');
			$templateConfigArr['blnGappDisable'] = $this->getRequest()->getPost('blnGappDisable');
			$templateConfigArr['blnGappEnable'] = $this->getRequest()->getPost('blnGappEnable');

			$taskTemplate->setTemplateConfig($templateConfigArr);
			TaskTemplate::delTaskTemplateByName($this->getRequest()->getPost('txtName'));
			TaskTemplate::addTaskTemplate($taskTemplate);
		}

		return;
	}

	public function getalltasksforuserAction()
	{
		$this->_helper->layout()->disableLayout();

		$this->view->defaults = new stdClass();

		$this->view->defaults->server = Config::singleton()->ssh_server;
		$this->view->defaults->serverpath = Config::singleton()->path_homefolders;
		$this->view->defaults->scanpath = Config::singleton()->path_share_scans;
		$this->view->defaults->wwwpath = Config::singleton()->path_share_www;
		$this->view->defaults->downloadpath =   Config::singleton()->path_share_downloads;
		$this->view->defaults->uploadpath =  Config::singleton()->path_share_uploads;

		$usersArr = $this->getRequest()->getPost('jspostArr',array());

		//check if we are called from template editor
		$templatename = $this->getRequest()->getPost('templatename',NULL);
		if ($templatename != null)
		{
			/*$template = TaskTemplate::getTemplateByName($templatename);
			$this->view->template = $template;

			$config = $template->getTemplateConfig();
			$this->view->config = $config;*/
			
			$this->view->templatename = $templatename;
			$this->view->manageTemplate = true;
		}
		else if($this->getRequest()->getParam('addtemplate') != null)
		{
			$this->view->addtemplate = true;
			$this->view->manageTemplate = true;
		}
		// single user selected
		else if(count($usersArr) < 2 && count($usersArr) > 0)
		{
			$this->view->singleAccountSelected = true;

			$userId = $usersArr[0];
			$platformAd = PlatformAD::getPlatformConfigByPerson($userId);
			$platformSs = NULL; //TODO!
			$platformGa = NULL; //TODO!

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
		}else if(count($usersArr > 1))
		{
			$this->view->multipleAccountsSelected = true;

			$this->view->hasAdAccount = true;

			$this->view->hasSsAccount = true;

			$this->view->hasGappAccount = true;
		}
		// geen
		else
			exit();

	}


}









