<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Group;
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
	const ActionUpdatePassword = 5;
	const ActionAddUserToGroup = 6;
	const ActionRemoveUserFromGroup = 7;

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
		else if($config["action"] == self::ActionUpdateUser && isset($config["user"]) && isset($config["enabled"]))
		{
			$ret = manageuser::updateUser($config["user"], $config["enabled"]);

			if($ret->isSucces())
			{
				$platform = PlatformGA::getPlatformConfigByPersonId($config["user"]);
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
				PlatformGA::removePlatformByPersonId($config["user"]->getId());
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
		else if($config["action"] == self::ActionUpdatePassword && isset($config["username"]) && isset($config["password"]))
		{
			$ret = manageuser::updatePassword($config["username"], $config["password"]);

			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionAddUserToGroup && isset($config["groupname"]) && isset($config["username"]))
		{
			$ret = manageuser::addUserToGroup($config["groupname"], $config["username"]);

			if($ret->isSucces())
				return true;
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveUserFromGroup && isset($config["groupname"]) && isset($config["username"]))
		{
			$ret = manageuser::removeUserFromGroup($config["groupname"], $config["username"]);

			if($ret->isSucces())
				return true;
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

		//                        if ($person->getPictureUrl() != null)
		//                            \solideagle\scripts\ga\usermanager::prepareSetPhoto($person);
		self::prepareAddUserToOu($person);
		self::prepareAddUserToGroup($person);

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

		//                        if ($person->getPictureUrl() != null)
		//                           self::prepareSetPhoto($person);

		$config["action"] = self::ActionUpdateUser;
		$config["user"] = $person;

		$config["enabled"] = $enabled;

		TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareChangePassword($person)
	{
		$config["action"] = self::ActionUpdatePassword;
		$config["username"] = $person->getAccountUsername();
		$config["password"] = $person->getAccountPassword();

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

	public static function prepareMoveUser($person, $newgroup, $oldgroup)
	{
		self::prepareRemoveUserFromGroup($person, $oldgroup);
		self::prepareAddUserToGroup($person);
		self::prepareAddUserToOu($person);
	}

	public static function prepareAddUserToGroup($person)
	{
		$config["action"] = self::ActionAddUserToGroup;
		$config["groupname"] = Group::getGroupById($person->getGroupId())->getName();
		$config["username"] = $person->getAccountUsername();

		TaskQueue::insertNewTask($config, $person->getId(), TaskQueue::TypePerson);
	}

	public static function prepareRemoveUserFromGroup($person, $group)
	{
		$config["action"] = self::ActionRemoveUserFromGroup;
		$config["groupname"] = $group->getName();
		$config["username"] = $person->getAccountUsername();

		TaskQueue::insertNewTask($config, $person->getId(), TaskQueue::TypePerson);
	}
}

?>
