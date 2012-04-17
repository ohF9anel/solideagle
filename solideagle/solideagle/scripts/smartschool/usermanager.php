<?php
namespace solideagle\scripts\smartschool;



use solideagle\data_access\TaskQueue;

use solideagle\plugins\smartschool\data_access\User;
use solideagle\data_access\TaskInterface;

class usermanager implements TaskInterface
{

	const ActionAddUser = 0;

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
			if (!isset($config["user"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}

			$ret = User::saveUser($config["user"]);
				
			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		

		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}


	public static function prepareAddUser($person)
	{
		$config["action"] = self::ActionAddUser;
		$config["user"] = User::convertPersonToSsUser($person);

		
		TaskQueue::insertNewTask($config, $person->getId());
		
	}



}
