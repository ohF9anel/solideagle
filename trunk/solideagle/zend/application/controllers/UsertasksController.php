<?php

use solideagle\Config;

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
    	
    
    	
    	
    	
    	
    	
    	
    	if($this->getRequest()->getPost('createAdAccount',false))
    	{
    		foreach($users as $user)
    		{
    			
    			
    			solideagle\scripts\ad\usermanager::prepareAddUser($user);
    		}
    		
    	}
    	
    	if($this->getRequest()->getPost('createSSAccount',false))
    	{
    		foreach($users as $user)
    		{
    			//solideagle\scripts\smartschool\usermanager::prepareAddUser($user);
    		}
    	}
    	
    	if($this->getRequest()->getPost('createGappAccount',false))
    	{
    		foreach($users as $user)
    		{
    			solideagle\scripts\ga\usermanager::prepareAddUser($user);
                        solideagle\scripts\ga\usermanager::prepareAddUserToOu($user);
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

    	return;
    }

    public function getalltasksforuserAction()
    {
    	$this->_helper->layout()->disableLayout();
    
      	 $this->view->defaults = new stdClass();
      	 
      	 $this->view->defaults->server = Config::$ssh_server;
      	 $this->view->defaults->serverpath = Config::$path_homefolders;
      	 $this->view->defaults->scanpath = Config::$path_share_scans;/
      	 $this->view->defaults->wwwpath = Config::$path_share_www;
      	 $this->view->defaults->downloadpath =   Config::$path_share_downloads;
      	 $this->view->defaults->uploadpath =  Config::$path_share_uploads;
    }


}









