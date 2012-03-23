<?php
namespace scripts\ad;

require_once 'data_access/TaskInserter.php';
require_once 'data_access/TaskQueue.php';
require_once 'plugins/ad/ManageOU.php';


use AD\ManageOU;

use DataAccess\TaskInserter;
use DataAccess\TaskQueue;


class oumanager implements \DataAccess\TaskInterface
{
	const ActionAdd = 0;
	const ActionDelete = 1;
	const ActionModify = 2;
	const ActionMove = 3;
	
	const myTaskId=27;

	

	
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

		}else if($config["action"] == self::ActionMove){
				
			return ManageOU::moveOU($config["newparents"], $config["oldparents"], $config["group"]);
			
		}else if($config["action"] ==  self::ActionModify){
				
			$ret = ManageOU::modifyOU($config["parents"],$config["oldgroup"],$config["newgroup"]);
			
			return $ret;

			
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
	
	public static function prepareAddGroup($parentgroups,$newgroup)
	{
		$config["action"] = self::ActionAdd;
		$config["parents"] = $parentgroups;
		$config["group"] = $newgroup;
		
		$taskInserter = new TaskInserter(self::myTaskId,$newgroup->getId(),TaskInserter::TypeGroup);
		
		$taskInserter->addToQueue($config);
	}
	
	public static function prepareDeleteGroup($parentgroups,$newgroup)
	{
		$config["action"] = self::ActionDelete;
		$config["parents"] = $parentgroups;
		$config["group"] = $newgroup;
		
		$taskInserter = new TaskInserter(self::myTaskId,$newgroup->getId(),TaskInserter::TypeGroup);
		
		$taskInserter->addToQueue($config);
	}
	
	public static function prepareModifyGroup($parentgroups,$oldroup,$newgroup)
	{
		$config["action"] = self::ActionModify;
		$config["oldgroup"] = $oldroup;
		$config["newgroup"] = $newgroup;
		$config["parents"] = $parentgroups;
		
		$taskInserter = new TaskInserter(self::myTaskId,$newgroup->getId(),TaskInserter::TypeGroup);
		
		$taskInserter->addToQueue($config);
	}
	
	public function prepareMoveGroup($newparentsgroup,$oldparentsgroup,$group)
	{
		$config["action"] = self::ActionMove;
		$config["newparents"] = $newparentsgroup;
		$config["oldparents"] = $oldparentsgroup;
		$config["group"] = $group;
		
		$taskInserter = new TaskInserter(self::myTaskId,$group->getId(),TaskInserter::TypeGroup);
		
		$taskInserter->addToQueue($config);
	}
	
	public function getParams()
	{
	
	}
	
	
}