<?php

use solideagle\scripts\imports\importclasses;

use solideagle\scripts\imports\importstudents;

use solideagle\scripts\imports\importstaff;

use solideagle\utilities\csvparser;

use solideagle\data_access\Person;

use solideagle\scripts\groupmanager;



use solideagle\data_access\Group;

use solideagle\plugins\ad\HomeFolder;

use solideagle\Config;

class ImportsController extends Zend_Controller_Action
{

	public function init()
	{
		/* Initialize action controller here */
	}

	public function indexAction()
	{
			

	}

	public function importklassenAction()
	{
		if($this->getRequest()->getParam("submit",false))
		{
			$this->_helper->redirector('showstudents', 'Imports');
		}else{
			$importNamespace = new Zend_Session_Namespace('importspace');
			
			//var_dump($importNamespace->studentsImport->classes);
			
			importclasses::createClasses($importNamespace->studentsImport->classes);
		}
	}

	public function showclassesAction()
	{
		
		$importNamespace = new Zend_Session_Namespace('importspace');
		
		$this->view->errormsg = "";
		
		if($this->getRequest()->getParam("submit",false))
		{
			
			foreach($this->getRequest()->getParam("class",array()) as $classname => $officialnumbers)
			{
				if(strlen($officialnumbers["instellingsnummer"] == 0))
				{
					$this->view->errormsg.="Instellingsnummer niet ingevuld bij " . $classname . "\n";
				}
				if(strlen($officialnumbers["administratievegroep"] == 0))
				{
					$this->view->errormsg.="Administratieve groep niet ingevuld bij " . $classname . "\n";
				}
				$importNamespace->studentsImport->classes[$classname]->instellingsnummer = $officialnumbers["instellingsnummer"];
				$importNamespace->studentsImport->classes[$classname]->administratievegroep= $officialnumbers["administratievegroep"];
			}
			
			if(strlen($this->view->errormsg) == 0)
			{
				$this->_helper->redirector('importklassen', 'Imports');
			}
		}
		
		$this->view->klassen = $importNamespace->studentsImport->classes;
		
	}

	public function importfinishedAction()
	{
		$importNamespace = new Zend_Session_Namespace('importspace');
		
		importstudents::addUsers($importNamespace->studentsImport->new);
		importstudents::updateUsers($importNamespace->studentsImport->updated);
		
		$importNamespace = NULL; //throw away the data;
	}

	public function importstudentsAction()
	{

		if($this->getRequest()->getParam("submit",false))
		{

			$adapter = new Zend_File_Transfer_Adapter_Http();

			$adapter->setDestination('/tmp');

			if (!$adapter->receive("csvfile")) {
				$messages = $adapter->getMessages();
				echo implode("\n", $messages);
				return;
			}

			$importNamespace = new Zend_Session_Namespace('importspace');
			
			//reset
			$importNamespace->studentsImport = NULL;
			
			$importNamespace->studentsImport = $this->importStudents($adapter->getFileName());
				
			//delete temp file to preserve space
			shell_exec("rm " . $adapter->getFileName());
				
			//code that was used to add informat id to existing users
			/*foreach($importNamespace->studentsImport->new as $studentsparams)
			{
				$person = Person::findPersonByNameandClass($studentsparams->name, $studentsparams->firstname,$studentsparams->klas);
				
				if($person != NULL)
				{
					$person->setInformatId($studentsparams->informatid);
					echo $person->getInformatId() . "\n";
					
					Person::updatePerson($person);
				}else{
					echo "Niet gevonden: ". $studentsparams->name. " " . $studentsparams->firstname . " " . $studentsparams->klas . "\n";
				}
			}*/
		

			
			
			//check for new classes
			if(count(($newclasses = importclasses::getNotExistingClasses($importNamespace->studentsImport->classes))) > 0)
			{
				//show import classes page
				$importNamespace->studentsImport->classes = $newclasses;
			
				$this->_helper->redirector('showclasses', 'Imports');
			}else if(count($importNamespace->studentsImport->new) != 0 || count($importNamespace->studentsImport->updated) != 0){
				//show import students page
				$this->_helper->redirector('showstudents', 'Imports');
			}else{
				echo "Geen wijzigingen gevonden!";
			}

		}

	}

	private function importStudents($filename)
	{
		$studentsimporter = new importstudents(fopen($filename,"rb"));
		return $studentsimporter->import();
	}

	public function showstudentsAction()
	{
		$importNamespace = new Zend_Session_Namespace('importspace');
		
		$this->view->studentsImport = $importNamespace->studentsImport;

		if($this->getRequest()->getParam("submit",false))
		{
			$this->_helper->redirector('importfinished', 'Imports');
			
			
			
		}
	}

	public function importpersonsAction()
	{
		if($this->getRequest()->getParam("submit",false))
		{
			$adapter = new Zend_File_Transfer_Adapter_Http();

			@mkdir(Config::singleton()->tempstorage);

			$adapter->setDestination(Config::singleton()->tempstorage);

			if (!$adapter->receive("csvfile")) {
				$messages = $adapter->getMessages();
				echo implode("\n", $messages);
				return;
			}

			$importNamespace = new Zend_Session_Namespace('importspace');
			
			$importNamespace->staffImport = NULL;
			
			$importNamespace->staffImport = $this->importStaff($adapter->getFileName());
				
			//delete temp file to preserve space
			shell_exec("rm " . $adapter->getFileName());

			$this->_helper->redirector('showstaff', 'Imports');
				
		}

	}

	private function importStaff($filename)
	{
		$staffImporter = new importstaff(fopen($filename,"rb"));
		return $staffImporter->import();
	}

	public function showstaffAction()
	{
		$importNamespace = new Zend_Session_Namespace('importspace');

		$this->view->staffImport = $importNamespace->staffImport;

		if($this->getRequest()->getParam("submit",false))
		{
			$this->_helper->redirector('createstaff', 'Imports');
		}

	}

	public function createstaffAction()
	{
		$importNamespace = new Zend_Session_Namespace('importspace');
		 
		importstaff::addUsers($importNamespace->staffImport->new);
		importstaff::updateUsers($importNamespace->staffImport->updated);
	}


}








