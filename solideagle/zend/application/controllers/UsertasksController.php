<?php

use solideagle\scripts\ad\usermanager;

use solideagle\data_access\Person;

class UsertasksController extends Zend_Controller_Action
{

    public function init()
    {
        /* Initialize action controller here */
    }

    public function indexAction()
    {
        // action body
    }

    public function gettasksforuserAction()
    {
        $data = $this->getRequest()->getParams();
        
        if(!isset($data["pid"]))
        	return;

      	 if(($person = Person::getPersonById($data["pid"])) === NULL)
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

    public function posttasksAction()
    {
       $data = $this->getRequest()->getParams();
       
       if(!isset($data["personid"]))
       	return;
       
       if(($person = Person::getPersonById($data["personid"])) === NULL)
       	return;
       
       usermanager::prepareAddHomeFolder(
       		$data["personid"], 
       		$data["server"], 
       		$person->getAccountUsername(),  
       		$data["serverpath"], 
       		$data["scanpath"], 
       		$data["wwwpath"], 
       		$data["downloadpath"], 
       		$data["uploadpath"]);
    }


}





