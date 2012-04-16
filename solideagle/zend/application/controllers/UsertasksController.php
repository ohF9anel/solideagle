<?php

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
    			//solideagle\scripts\ss\usermanager::prepareAddUser($user);
    		}
    	}
    	
    	if($this->getRequest()->getPost('createGappAccount',false))
    	{
    		foreach($users as $user)
    		{
    			//solideagle\scripts\gapp\usermanager::prepareAddUser($user);
    		}
    	}
    	

    	
    	
    	return;
    }

    public function getalltasksforuserAction()
    {
    	$this->_helper->layout()->disableLayout();
    
        if(($person = Person::getPersonById(4)) === NULL)
      	 	return;
      	 
      	 $this->view->person=$person;
      	 
      	 $this->view->defaults = new stdClass();
      	 
      	 $this->view->defaults->server = "s1.solideagle.lok";
      	 $this->view->defaults->serverpath = "c:\homefolders";
      	 $this->view->defaults->scanpath = "c:\scans";
      	 $this->view->defaults->wwwpath = "c:\www";
      	 $this->view->defaults->downloadpath = "c:\downloads";
      	 $this->view->defaults->uploadpath = "c:\uploads";
    }


}









