<?php

use solideagle\scripts\groupmanager;

use solideagle\data_access\Type;

use solideagle\data_access\Group;

use solideagle\plugins\ad\HomeFolder;

use solideagle\Config;

use solideagle\plugins\ad\SSHManager;

class ImportsController extends Zend_Controller_Action
{

    public function init()
    {
		/* Initialize action controller here */
    }

    public function indexAction()
    {
		
		
		
		/*$this->_helper->layout()->disableLayout();
		
	
		$conn = SSHManager::singleton()->getConnection("S1.solideagle.lok");
		
		HomeFolder::createHomeFolder($conn,"S1.solideagle.lok", "c:\homefolders", "testuser1");
		
		$conn->exitShell();
		
		$conn->read();*/
    }

    public function importklassenAction()
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
				
			$file = fopen($adapter->getFileName(),"r");
			
			$klasCount = 0;
			$fileOk = false;
			
			//kijk of de eerste lijn de kolom klas bevat
			foreach (fgetcsv($file,0,";") as $linearrelem)
			{
				$klasCount += 1;
				if(strcasecmp($linearrelem, "KLAS") === 0)
				{
					$fileOk = true;
					break;
				}
					
			}
			
			if(!$fileOk)
			{
				echo "Kolom KLAS niet gevonden!";
				return;
			}
			
		//	echo $klasCount;
			
				
			//echo "<pre>";
			
			$klassArr = array();
			
			
			while(!feof($file))
			{
				$arr = fgetcsv($file,0,";");
				
				//sanity check
				if(count($arr) < 5)
					continue;
				
				$klas =  $arr[$klasCount-1];

				$klas = str_replace(" ", "", $klas);
				
				//filter some more!
				if($klas !== "" && (strpos(strtoupper($klas), "OUT") === false) )
				{
					if(!isset($klassArr[$klas])) //php array abused as unique key map
						$klassArr[$klas] = $klas;
				}
			
			}
			
			sort($klassArr,SORT_STRING);
			
			$importNamespace = new Zend_Session_Namespace('importspace');
			
			$importNamespace->klasArr = $klassArr;
			
			//var_dump($klassArr);
			
			//echo "</pre>";
			
			fclose($file);
			
			shell_exec("rm " . $adapter->getFileName());
			
			$this->_helper->redirector('showclasses', 'Imports');
		}
		 

    }

    public function showclassesAction()
    {
    	
    	if($this->getRequest()->getParam("submit",false))
    	{
    		
    		foreach($this->getRequest()->getParam("klassen",array()) as $klas)
    		{
    			$group = new Group();
    			$group->setParentId(3);
    			$group->setName($klas);
    			$group->addType(new Type(Type::TYPE_LEERLING));
    			
    			$newgroupid = Group::addGroup($group);
    			
    			$group->setId($newgroupid);
    			
    			groupmanager::Add(Group::getParents($group), $group);
    		}
    		
    		$this->_helper->redirector('importfinished', 'Imports');
    		
    	}else{
    		$importNamespace = new Zend_Session_Namespace('importspace');
    		$this->view->klassen = $importNamespace->klasArr;
    	}

    		
    	
     
  
		
    }

    public function importfinishedAction()
    {
        // action body
    }


}







