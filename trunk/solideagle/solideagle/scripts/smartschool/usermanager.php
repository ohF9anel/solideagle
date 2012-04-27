<?php
namespace solideagle\scripts\smartschool;


use solideagle\data_access\PlatformSS;

use solideagle\data_access\Group;
use solideagle\data_access\Person;

use solideagle\data_access\platforms;
use solideagle\data_access\TaskQueue;

use solideagle\plugins\smartschool\data_access\User;
use solideagle\data_access\TaskInterface;

class usermanager implements TaskInterface
{

		const ActionAddUser = 0;
        const ActionUpdateUser = 1;
        const ActionRemoveUser = 2;
        const ActionMoveUser = 3;

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if(!isset($config["action"]))
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}

		if($config["action"] == self::ActionAddUser)
		{
			if (!isset($config["person"]) || !isset($config["groupname"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}

			$ret = User::saveUser(User::convertPersonToSsUser($config["person"],$config["groupname"]));
				
			if($ret->isSucces())
			{
				$platformss = new PlatformSS();
				$platformss->setPersonId($config["person"]->getId());
				PlatformSS::addToPlatform($platformss);      
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}else if($config["action"] == self::ActionUpdateUser)
		{
			if (!isset($config["person"]) || !isset($config["groupname"]) || !isset($config["enabled"]));
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}
			
			$ret = User::updateUser(User::convertPersonToSsUser($config["person"],$config["groupname"],$config["enabled"]));
			
			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
			
		}
		else if($config["action"] == self::ActionRemoveUser)
		{
			if (!isset($config["person"]));
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}
			
			//we don't need the groupname for removing
			$ret = User::removeUser(User::convertPersonToSsUser($config["person"],""));
				
			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionMoveUser)
		{
			
		}
		

		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}

	/**
	 * 
	 * @param Person $person
	 */
	public static function prepareAddUser($person)
	{
		$config["action"] = self::ActionAddUser;
		$config["person"] = $person;
		$group = Group::getGroupById($person->getGroupId());
		if(isset($group))
		{
			$config["groupname"] = $group->getName();
			TaskQueue::insertNewTask($config, $person->getId());
		}else{
			//we do not support people having no group
		}
	}
	
	public static function prepareUpdateUser($person,$accountenabled)
	{
		$config["action"] = self::ActionUpdateUser;
		$config["person"] = $person;
		$config["enabled"] = $accountenabled;
		$group = Group::getGroupById($person->getGroupId());
		if(isset($group))
		{
			$config["groupname"] = $group->getName();
			TaskQueue::insertNewTask($config, $person->getId());
		}else{
			//we do not support people having no group
		}
	}
	
	public static function prepareRemoveUser($person)
	{
		$config["action"] = self::ActionRemoveUser;
		$config["person"] = $person;
		TaskQueue::insertNewTask($config, $person->getId());
	}
	
	/**
	 * 
	 * @param Person $person
	 * @param Group $newgroup
	 * @param Group $oldgroup
	 */
	public static function prepareMoveUser($person,$newgroup,$oldgroup)
	{
		$config["action"] = self::ActionMoveUser;
		$config["person"] = $person;
		$config["newgroup"] = $newgroup;
		
		TaskQueue::insertNewTask($config, $person->getId());
	}


}
