<?php
namespace solideagle\scripts\smartschool;


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
			if (!isset($config["person"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}

			$ret = User::saveUser(User::convertPersonToSsUser($config["person"]));
				
			if($ret->isSucces())
			{
                                $platform = new platforms();
                                $platform->setPlatformType(platforms::PLATFORM_SMARTSCHOOL);
                                $platform->setPersonId($config["person"]->getId());
                                $platform->setEnabled($config["person"]->getAccountActive());
                                platforms::addPlatform($platform);
                                return true;
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
		$config["person"] = $person;

		TaskQueue::insertNewTask($config, $person->getId());
	}


}
