<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Person;
use solideagle\data_access\PlatformGA;

use solideagle\plugins\ga\manageuser;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class usermanager implements TaskInterface
{
	const ActionAddUser = 0;
	const ActionUpdateUser = 1;
	const ActionDelUser = 2;
	const ActionAddUserToOu = 3;
        const ActionSetPhoto = 4;

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddUser && isset($config["user"]) && isset($config["enabled"]))
		{
			$ret = manageuser::addUser($config["user"], $config["enabled"]);

			if($ret->isSucces())
			{
				$platform = new PlatformGA();
				$platform->setPersonId($config["user"]->getId());
				$platform->setEnabled($config["enabled"]);
				PlatformGA::addToPlatform($platform);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionUpdateUser && isset($config["user"]) && isset($config["oldUsername"]) && isset($config["enabled"]))
		{
			$ret = manageuser::updateUser($config["user"], $config["enabled"]);

			if($ret->isSucces())
			{
				$platform = new PlatformGA();
				$platform->setPersonId($config["user"]->getId());
				$platform->setEnabled($config["enabled"]);
				PlatformGA::updatePlatform($platform);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionDelUser && isset($config["user"]))
		{
			$ret = manageuser::removeUser($config["user"]);

			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionAddUserToOu && isset($config["user"]))
		{
			$ret = manageuser::addUserToOu($config["user"]);

			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
                else if($config["action"] == self::ActionSetPhoto && isset($config["username"]) && isset($config["pictureurl"]))
		{
                        $picturepath = manageuser::downloadTempFile($config["pictureurl"], "/var/www/tmp/", "png.png");
			$ret = manageuser::setPhoto($config["username"], $picturepath);
                        
                        

			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
	}

	public static function prepareAddUser($person, $enabled = true)
	{
		$config["action"] = self::ActionAddUser;
		$config["user"] = $person;
		$config["enabled"] = $enabled;

		TaskQueue::insertNewTask($config, $person->getId());
	}

	
	/**
	*
	*
	* @param Person $person
	* @param bool $enabled
	*/
	public static function prepareUpdateUser($person, $enabled)
	{
		$config["action"] = self::ActionUpdateUser;
		$config["user"] = $person;
		
		$config["enabled"] = $enabled;

		TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareDelUser($person)
	{
		$config["action"] = self::ActionDelUser;
		$config["user"] = $person;
		TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareAddUserToOu($person)
	{
		$config["action"] = self::ActionAddUserToOu;
		$config["user"] = $person;
		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareSetPhoto($person)
	{
		$config["action"] = self::ActionSetPhoto;
		$config["username"] = $person->getAccountUsername();
                $config["pictureurl"] = $person->getPictureUrl();
		TaskQueue::insertNewTask($config, $person->getId());
	}
}

?>
