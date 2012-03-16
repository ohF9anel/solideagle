<?php


require_once 'scripts/scriptInterface.php';
require_once 'plugins/ad/ManageOU.php';
require_once 'data_access/Group.php';

use AD\ManageOU;
use DataAccess\Group;

class oumanager implements scriptInterface
{
	public function getParams()
	{
		return array(
				"action" => array("enum",array("Add","Move","Modify","Delete"))
		);
	}

	/**
	 * (non-PHPdoc)
	 * @see scriptInterface::runScript()
	 */
	public function runScript($grouptaskqueue)
	{

		$params = $grouptaskqueue->getConfiguration();

		echo"<pre>";
		//var_dump($grouptaskqueue);
			
		var_dump($params);
		echo"</pre>";
			

		if(!isset($params["action"]))
		{
			$grouptaskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}

		if($params["action"] == "Add")
		{
			$group = Group::getGroupById($grouptaskqueue->getGroup_Id());

			$stdobj = ManageOU::addOU($group);

			if(!$stdobj[0])
			{				
				$grouptaskqueue->setErrorMessages("OU toevoegen mislukt. Error: ");
				$grouptaskqueue->addErrorMessage($stdobj[1]);
				return false; //it failed for some reason
			}

		}else if($params["action"] == "Move"){
			
			$grouptaskqueue->setErrorMessages("Not Implemented Yet");
		}else if($params["action"] == "Modify"){
			
			$grouptaskqueue->setErrorMessages("Not Implemented Yet");
		}else if($params["action"] == "Delete"){
			
			$grouptaskqueue->setErrorMessages("Not Implemented Yet");
		}else{

			$grouptaskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
			
		//create on AD
			
		$grouptaskqueue->setRollback_Configuration(array("action" => "Delete"));
			
			
			

		return true; //succes
	}



}

?>