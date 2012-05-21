<?php
namespace solideagle\scripts\ad;

use solideagle\plugins\ad\ManageOU;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;
use solideagle\logging\Logger;


class oumanager implements TaskInterface
{
	const ActionAdd = "AddOU";
	const ActionDelete = "DeleteOU";
	const ActionModify = "ModifyOU";
	const ActionMove = "MoveOU";

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();
		
		if(!isset($config["action"]))
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}
		
		if($config["action"] == self::ActionAdd && isset($config["parents"]) && isset($config["group"]))
		{
                        Logger::log("Trying to create OU \"" . $config["group"]->getName() . "\" in OU \"" . $config["parents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
			$ret = ManageOU::addOU($config["parents"],$config["group"]);
			
			if($ret->isSucces())
			{
                                Logger::log("Successfully created OU \"" . $config["group"]->getName() . "\" in OU \"" . $config["parents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
				return true;	
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}

		}else if($config["action"] == self::ActionMove && isset($config["group"]) && isset($config["oldparents"]) && isset($config["newparents"])){
				
                        Logger::log("Trying to move OU \"" . $config["group"]->getName() . "\" from OU \"" . $config["oldparents"][0]->getName() . "\" to OU \"" . $config["newparents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
			$ret = ManageOU::moveOU($config["oldparents"], $config["newparents"], $config["group"]);
                        if($ret->isSucces())
			{
                                Logger::log("Successfully moved OU \"" . $config["group"]->getName() . "\" from OU \"" . $config["oldparents"][0]->getName() . "\" to OU \"" . $config["newparents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
				return true;	
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
			
		}else if($config["action"] ==  self::ActionModify && isset($config["parents"]) && isset($config["oldgroup"]) && isset($config["newgroup"])){
			Logger::log("Trying to modify OU \"" . $config["oldgroup"]->getName() . "\" to OU \"" . $config["newgroup"]->getName() . "\" in OU \"" . $config["parents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);                        
			$ret = ManageOU::modifyOU($config["parents"],$config["oldgroup"],$config["newgroup"]);
			
			if($ret->isSucces())
			{
                                Logger::log("Successfully modified OU \"" . $config["oldgroup"]->getName() . "\" to OU \"" . $config["newgroup"]->getName() . "\" in OU \"" . $config["parents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
				return true;	
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}

			
		}else if($config["action"] == self::ActionDelete && isset($config["group"]) && isset($config["parents"]))
                    {
                        Logger::log("Trying to remove OU \"" . $config["group"]->getName() . "\" in OU \"" . $config["parents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
			$ret = ManageOU::removeOU($config["parents"],$config["group"]);
			if($ret->isSucces())
			{
                                Logger::log("Successfully removed OU \"" . $config["group"]->getName() . "\" in OU \"" . $config["parents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
				return true;	
			}else{
				$taskqueue->setErrorMessages("Failed removing OU \"" . $config["group"]->getName() . "\" in OU \"" . $config["parents"][0]->getName() . "\" in Active Directory.");
				return false;
			}
		}else{
		
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
		
		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}

	public static function prepareAddOu($parentgroups,$newgroup)
	{
		$config["action"] = self::ActionAdd;
		$config["parents"] = $parentgroups;
		$config["group"] = $newgroup;
		

		
		TaskQueue::insertNewTask($config,$newgroup->getId(),TaskQueue::TypeGroup);
	}
	
	public static function prepareDeleteOu($parentgroups,$newgroup)
	{
		$config["action"] = self::ActionDelete;
		$config["parents"] = $parentgroups;
		$config["group"] = $newgroup;
		
		TaskQueue::insertNewTask($config,$newgroup->getId(),TaskQueue::TypeGroup);
	}
	
	public static function prepareModifyOu($parentgroups,$oldgroup,$newgroup)
	{
		$config["action"] = self::ActionModify;
		$config["oldgroup"] = $oldgroup;
		$config["newgroup"] = $newgroup;
		$config["parents"] = $parentgroups;
		
		TaskQueue::insertNewTask($config,$newgroup->getId(),TaskQueue::TypeGroup);
	}
	
	public static function prepareMoveOu($newparentsgroup,$oldparentsgroup,$group)
	{
		$config["action"] = self::ActionMove;
		$config["newparents"] = $newparentsgroup;
		$config["oldparents"] = $oldparentsgroup;
		$config["group"] = $group;
		
		TaskQueue::insertNewTask($config,$group->getId(),TaskQueue::TypeGroup);
	}
}