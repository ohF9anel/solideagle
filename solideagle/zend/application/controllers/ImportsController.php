<?php

use solideagle\data_access\Person;

use solideagle\scripts\groupmanager;

use solideagle\data_access\Type;

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

			$adapter = new Zend_File_Transfer_Adapter_Http();

			
			@mkdir(Config::singleton()->tempstorage);
			
			$adapter->setDestination(Config::singleton()->tempstorage);

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
			$studentsParentGroup = Group::getGroupById($this->getRequest()->getParam("selectGroup"));

			if($studentsParentGroup === NULL)
			{
				echo "Deze groep bestaat niet";
				return;
			}

			$groupStructureResults = new stdClass();

			//Check if structure of groups exists
			foreach(Group::getChilderen($studentsParentGroup) as $childGroup)
			{
				if($childGroup->getName() == "graad 1")
				{
					$groupStructureResults->eerstegraad = $childGroup;
					foreach(Group::getChilderen($childGroup) as $subchildGroup)
					{
						if($subchildGroup->getName() == "eerstes")
						{
							$groupStructureResults->eerstes = $subchildGroup;
						}else if($subchildGroup->getName() == "tweedes")
						{
							$groupStructureResults->tweedes = $subchildGroup;
						}
					}
				}
				else if($childGroup->getName() == "graad 2")
				{
					$groupStructureResults->tweedegraad = $childGroup;
					foreach(Group::getChilderen($childGroup) as $subchildGroup)
					{
						if($subchildGroup->getName() == "derdes")
						{
							$groupStructureResults->derdes = $subchildGroup;
						}else if($subchildGroup->getName() == "vierdes")
						{
							$groupStructureResults->vierdes = $subchildGroup;
						}
					}
				}
				else if($childGroup->getName() == "graad 3")
				{
					$groupStructureResults->derdegraad = $childGroup;
					foreach(Group::getChilderen($childGroup) as $subchildGroup)
					{
						if($subchildGroup->getName() == "vijfdes")
						{
							$groupStructureResults->vijfdes = $subchildGroup;
						}else if($subchildGroup->getName() == "zesdes")
						{
							$groupStructureResults->zesdes = $subchildGroup;
						}
					}
				}
			}

			//create structure if necessary



			if(!isset($groupStructureResults->eerstegraad ))
			{
				$group = new Group();
				$group->setName("graad 1");
				$group->setParentId($studentsParentGroup->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->eerstegraad = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->tweedegraad ))
			{
				$group = new Group();
				$group->setName("graad 2");
				$group->setParentId($studentsParentGroup->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->tweedegraad = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->derdegraad ))
			{
				$group = new Group();
				$group->setName("graad 3");
				$group->setParentId($studentsParentGroup->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->derdegraad = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->eerstes ))
			{
				$group = new Group();
				$group->setName("eerstes");
				$group->setParentId($groupStructureResults->eerstegraad->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->eerstes = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->tweedes ))
			{
				$group = new Group();
				$group->setName("tweedes");
				$group->setParentId($groupStructureResults->eerstegraad->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->tweedes = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->derdes ))
			{
				$group = new Group();
				$group->setName("derdes");
				$group->setParentId($groupStructureResults->tweedegraad->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->derdes = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->vierdes ))
			{
				$group = new Group();
				$group->setName("vierdes");
				$group->setParentId($groupStructureResults->tweedegraad->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->vierdes = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->vijfdes ))
			{
				$group = new Group();
				$group->setName("vijfdes");
				$group->setParentId($groupStructureResults->derdegraad->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->vijfdes = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			if(!isset($groupStructureResults->zesdes ))
			{
				$group = new Group();
				$group->setName("zesdes");
				$group->setParentId($groupStructureResults->derdegraad->getId());
				$gid = Group::addGroup($group);
				$group->setId($gid);
				$groupStructureResults->zesdes = $group;
				groupmanager::Add(Group::getParents($group), $group);
			}

			foreach($this->getRequest()->getParam("klassen",array()) as $klas)
			{
				$group = new Group();
					
				switch (substr($klas,0,1)) {
					case 1:
						$group->setParentId($groupStructureResults->eerstes->getId());
						break;
					case 2:
						$group->setParentId($groupStructureResults->tweedes->getId());
						break;
					case 3:
						$group->setParentId($groupStructureResults->derdes->getId());
						break;
					case 4:
						$group->setParentId($groupStructureResults->vierdes->getId());
						break;
					case 5:
						$group->setParentId($groupStructureResults->vijfdes->getId());
						break;
					case 6:
						$group->setParentId($groupStructureResults->zesdes->getId());
						break;
							
					default:
						$group->setParentId($studentsParentGroup->getId());
						break;
				}
				
				$group->setName($klas);
				$group->addType(new Type(Type::TYPE_LEERLING));

				$newgroupid = Group::addGroup($group);

				$group->setId($newgroupid);

				groupmanager::Add(Group::getParents($group), $group);
			}

			$this->_helper->redirector('importfinished', 'Imports');

		}else{
			$importNamespace = new Zend_Session_Namespace('importspace');

			if(!isset($importNamespace->klasArr))
			{
				echo "Niets om te importeren, begin opnieuw";
				return;
			}

			$this->view->klassen = array();
			$this->view->groups = Group::getAllGroups();

			//only show classes that do not exist yet
			foreach($importNamespace->klasArr as $origklas)
			{
				if(!Group::doesGroupExistByName($origklas))
				{
					$this->view->klassen[] = $origklas;
				}
			}


		}
	}

	public function importfinishedAction()
	{
		// action body
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

			$file = fopen($adapter->getFileName(),"r");

			$counter = 0;

			$klasCount = -1;
			$voornaamCount = -1;
			$naamCount = -1;
			$codeCount = -1; //jaartal halen we hieruit door een aantal trucjes toe te passen

			//zoek de kolommen waar we in geinterreseerd zijn
			foreach (fgetcsv($file,0,";") as $linearrelem)
			{
				$counter += 1;
				if(strcasecmp($linearrelem, "KLAS") === 0)
				{
					$klasCount = $counter;

				}elseif (strcasecmp($linearrelem, "EersteVoorNaam") === 0){
					$voornaamCount = $counter;
				}elseif (strcasecmp($linearrelem, "FamNaam") === 0){
					$naamCount = $counter;
				}elseif (strcasecmp($linearrelem, "CODE") === 0){
					$codeCount = $counter;
				}

			}

			if($klasCount < 0)
			{
				echo "Kolom KLAS niet gevonden!";
				return;
			}

			if($voornaamCount < 0)
			{
				echo "Kolom EERSTEVOORNAAM niet gevonden!";
				return;
			}

			if($naamCount < 0)
			{
				echo "Kolom FAMNAAM niet gevonden!";
				return;
			}

			if($codeCount < 0)
			{
				echo "Kolom CODE niet gevonden!";
				return;
			}

			$stdLlnArrAdd = array();
			$stdLlnArrMove = array();
			$stdLlnArrFail = array();

			while(!feof($file))
			{
				$arr = fgetcsv($file,0,";");

				//sanity check
				if(count($arr) < 5)
					continue;

				$klas =  $arr[$klasCount-1];

				$voornaam =  $arr[$voornaamCount-1];

				$naam = $arr[$naamCount-1];

				$code = $arr[$codeCount-1];

				$jaar = $this->parseCode($code);

				$klas = str_replace(" ", "", $klas);

				if($klas !== "" && (strpos(strtoupper($klas), "OUT") === false) )
				{
					if($jaar === false)
					{
						$stdLlnArrFail[] = "Fout met jaar: " . $jaar . " " . $voornaam . " " . $naam . "\n";
					}else{

						$stdLln = new stdClass();
						$stdLln->naam = $naam;
						$stdLln->voornaam = $voornaam;
						$stdLln->jaar = $jaar;
						$stdLln->klas = $klas;

						//student exists?
						if(($person = Person::findPersonByName($naam,$voornaam)) != NULL)
						{
							//student still in same group?
							if(strcasecmp(Group::getGroupById($person->getGroupId())->getName(),$stdLln->klas) == 0)
							{
								
								continue; //nothing changed, do nothing
							}
							
							//this student should be moved
							//moving not implemented yet
							
							//$stdLln->person = $person;
							//$stdLlnArrMove[] = $stdLln;
						}else{
							$stdLlnArrAdd[] = $stdLln;
						}

					}
				}
			}

			$importNamespace = new Zend_Session_Namespace('importspace');

			$importNamespace->llnAddArr = $stdLlnArrAdd;
			$importNamespace->llnMoveArr = $stdLlnArrMove;
			$importNamespace->llnFailArr = $stdLlnArrFail;

			fclose($file);

			shell_exec("rm " . $adapter->getFileName());

			$this->_helper->redirector('showstudents', 'Imports');

		}

	}


	//thise code field is a serious WTF, so this parser is too!
	private function parseCode($code)
	{
		if(!(substr($code, 0,1) == "N"))//code must start with N
		{
			return false;
		}
		
		//only get the numbers
		$cleanedcode = "";
		for($i=0; $i<strlen($code); $i++)
		{
			if(is_numeric($code[$i]))
			{
				$cleanedcode.=$code[$i];
			}
		}

		if(strlen($cleanedcode) == 2)
		{
			return $cleanedcode;
		}
		else //4 digit code
		{
			return substr($cleanedcode, 2,2);

		}

	}

	public function showstudentsAction()
	{
		$importNamespace = new Zend_Session_Namespace('importspace');

		//Defaults, prevent display errors
		$this->view->llnToAdd =array();
		$this->view->llnToMove =array();
		
		if($this->getRequest()->getParam("submit",false))
		{
			$checkedArr = $this->getRequest()->getParam("llnAddKeys",array());
				
			//add students
			foreach($importNamespace->llnAddArr as $key => $addLln)
			{
				$newLln = new Person();
				$newLln->setName($addLln->naam);
				$newLln->setFirstName($addLln->voornaam);
				$newLln->setYear($addLln->jaar);
				$newLln->setAccountUsername(Person::generateUsername($newLln,true));
				$newLln->setAccountPassword(Person::generatePassword());

				$memberOfGroup = Group::getGroupByName($addLln->klas);

				$newLln->setGroupId($memberOfGroup->getId());

				//var_dump($newLln);
				
				//make adding not timeout, give db 5 seconds to insert
				set_time_limit(5);
				Person::addPerson($newLln);
			}
				
			//move students
				
		}else{
			$this->view->llnToAdd =$importNamespace->llnAddArr;
			$this->view->llnToMove =$importNamespace->llnMoveArr;
			$this->view->llnFail = $importNamespace->llnFailArr;
		}

	}
}


