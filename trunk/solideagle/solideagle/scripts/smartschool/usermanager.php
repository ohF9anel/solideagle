<?php
namespace solideagle\scripts\smartschool;


use solideagle\Config;

use solideagle\logging\Logger;

use solideagle\scripts\GlobalUserManager;

use solideagle\data_access\PlatformSS;

use solideagle\data_access\Group;
use solideagle\data_access\Person;

use solideagle\data_access\platforms;
use solideagle\data_access\TaskQueue;

use solideagle\plugins\smartschool\data_access\User;
use solideagle\data_access\TaskInterface;

class usermanager implements TaskInterface
{

	const ActionAddUser = "AddUser";
	const ActionUpdateUser = "UpdateUser";
	const ActionRemoveUser = "RemoveUser";
	const ActionMoveUser = "MoveUser";
	const ActionUpdatePassword = "UpdatePassword";
	const ActionSetPhoto = "SetPhoto";

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
				GlobalUserManager::cleanPasswordIfAllAccountsExist($config["person"]->getId());
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionUpdateUser)
		{
			if (!isset($config["person"]) || !isset($config["groupname"]) || !isset($config["enabled"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}

			$ret = User::updateUser(User::convertPersonToSsUser($config["person"],$config["groupname"],$config["enabled"]));

			if($ret->isSucces())
			{
				$platformss =  PlatformSS::getPlatformConfigByPersonId($config["person"]->getId());
				$platformss->setEnabled($config["enabled"]);
				PlatformSS::updatePlatform($platformss);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveUser)
		{
			if (!isset($config["person"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}

			//we don't need the groupname for removing
			$ret = User::removeUser(User::convertPersonToSsUser($config["person"],""));

			if($ret->isSucces())
			{
				PlatformSS::removePlatformByPersonId($config["person"]->getId());
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}else if($config["action"] == self::ActionMoveUser)
		{
			if (!isset($config["person"]) || !isset($config["groupname"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}
				
			$ret = User::moveUser(User::convertPersonToSsUser($config["person"],$config["groupname"]),$config["oldgroupname"]);
				
			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionSetPhoto && isset($config["person"]))
		{
			$ret = \solideagle\data_access\helpers\imagehelper::downloadTempFile($config["person"]->getPictureUrl(), Config::singleton()->tempstorage . "tempPic");
			if ($ret->isSucces())
			{
				$encodedPhoto = \solideagle\data_access\helpers\imagehelper::encodeImage(Config::singleton()->tempstorage . "tempPic");

				$ret = User::setPhoto($config["person"],$encodedPhoto);

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
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if ($config["action"] == self::ActionUpdatePassword)
		{
			if (!isset($config["person"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}
			
			Logger::log("Updating passord for user " . $config["person"]->getAccountusername(),PEAR_LOG_INFO);
			
			$ret = User::updatePassword(User::convertPersonToSsUser($config["person"]));
			
			if($ret->isSucces())
			{
				GlobalUserManager::cleanPasswordIfAllAccountsExist($config["person"]->getId());
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}

		$taskqueue->setErrorMessages("Probleem met action type");
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
		if ($person->getPictureUrl() != null)
			self::prepareSetPhoto($person);
	}

	public static function prepareUpdateUser($person, $oldPerson, $accountenabled)
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

		if ($person->getPictureUrl() != null && $person->getPictureUrl() != $oldPerson->getPictureUrl())
			self::prepareSetPhoto($person);
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
		$config["groupname"] = $newgroup->getName();
		$config["oldgroupname"] = $oldgroup->getName();

		TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareChangePassword($person)
	{
		$config["action"] = self::ActionUpdatePassword;
		$config["person"] = $person;
		TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareSetPhoto($person)
	{
		$config["action"] = self::ActionSetPhoto;
		$config["person"] = $person;

		TaskQueue::insertNewTask($config, $person->getId());
	}



}
