<?php
namespace solideagle\scripts\ad;

use solideagle\plugins\ad\ManageOU;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class oumanager implements TaskInterface
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
			
			if($ret->isSucces())
			{
				return true;	
			}else{
				$taskqueue->setErrorMessages($ret->getError());
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
		

		
		TaskQueue::insertNewTask($config,$newgroup->getId(),TaskQueue::TypeGroup);
	}
	
	public static function prepareDeleteGroup($parentgroups,$newgroup)
	{
		$config["action"] = self::ActionDelete;
		$config["parents"] = $parentgroups;
		$config["group"] = $newgroup;
		
		TaskQueue::insertNewTask($config,$newgroup->getId(),TaskQueue::TypeGroup);
	}
	
	public static function prepareModifyGroup($parentgroups,$oldgroup,$newgroup)
	{
		$config["action"] = self::ActionModify;
		$config["oldgroup"] = $oldgroup;
		$config["newgroup"] = $newgroup;
		$config["parents"] = $parentgroups;
		
		TaskQueue::insertNewTask($config,$newgroup->getId(),TaskQueue::TypeGroup);
	}
	
	public static function prepareMoveGroup($newparentsgroup,$oldparentsgroup,$group)
	{
		$config["action"] = self::ActionMove;
		$config["newparents"] = $newparentsgroup;
		$config["oldparents"] = $oldparentsgroup;
		$config["group"] = $group;
		
		TaskQueue::insertNewTask($config,$newgroup->getId(),TaskQueue::TypeGroup);
	}
	
	public function getParams()
	{
                
	}
	
	
}