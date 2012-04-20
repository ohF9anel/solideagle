<?php

use solideagle\scripts\smartschool\usermanager;

use solideagle\Config;

use solideagle\data_access\platforms;

use solideagle\data_access\Type;

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


	public function posttasksAction()
	{
		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
		 
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
                
                // disable account?
//                if($this->getRequest()->getPost('blnGappDisable',false))
//		{
//			foreach($users as $user)
//			{
//				solideagle\scripts\ad\usermanager::prepareUpdateUser($user, false);
//			}
//		}
		 
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

                // single user selected
		if(count($usersArr) < 2 && count($usersArr) > 0)
		{
                        $this->view->singleAccountSelected = true;
                    
			$userId = $usersArr[0];
			$platformAd = platforms::getPlatformAdByPersonId($userId);
                        $platformSs = platforms::getPlatformSmartschoolByPersonId($userId);
                        $platformGa = platforms::getPlatformGappByPersonId($userId);
                        
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









