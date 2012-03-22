<?php
namespace adplugin;

require_once 'data_access/BaseTask.php';
require_once 'data_access/TaskQueue.php';
require_once 'plugins/ad/ManageOU.php';


use AD\ManageOU;

use DataAccess\BaseTask;
use DataAccess\TaskQueue;


class groupmanager extends \DataAccess\BaseTask
{
	const ActionAdd = 0;
	const ActionDelete = 1;

	
	public function __construct($taskid = NULL,$groupid= NULL)
	{
		parent::__construct($taskid, $groupid, parent::TypeGroup);
	}
	

	
	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();
		
		if(!isset($config["action"]))
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}
		
		if($config["action"] == self::ActionAdd)
		{
			
			
			
			$ret = ManageOU::addOU($config["parents"],$config["group"]);
			
			if($ret[0] === true)
			{
				return true;	
			}else{
				$taskqueue->setErrorMessages($ret[1]);
				return false;
			}

		}else if($config["action"] == "Move"){
				
			$taskqueue->setErrorMessages("Not Implemented Yet");
			return false;
		}else if($config["action"] == "Modify"){
				
			$taskqueue->setErrorMessages("Not Implemented Yet");
			return false;
		}else if($config["action"] == self::ActionDelete){

			if(ManageOU::removeOU($config["parents"],$config["group"]))
			{
				return true;
			}
			
			$taskqueue->setErrorMessages("Verwijderen mislukt");
			return false;
		}else{
		
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
		
		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}
	
	public function createTaskFromParams($params)
	{
	
	}
	
	public  function prepareAddGroup($parentgroups,$newgroup)
	{
		$config["action"] = self::ActionAdd;
		$config["parents"] = $parentgroups;
		$config["group"] = $newgroup;
		
		$this->addToQueue($config);
	}
	
	public  function prepareDeleteGroup($parentgroups,$newgroup)
	{
		$config["action"] = self::ActionDelete;
		$config["parents"] = $parentgroups;
		$config["group"] = $newgroup;
		
		$this->addToQueue($config);
	}
	
	public function getParams()
	{
	
	}
	
	
}