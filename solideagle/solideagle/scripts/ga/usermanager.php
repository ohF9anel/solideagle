<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Group;
use solideagle\data_access\Person;
use solideagle\data_access\Type;
use solideagle\data_access\PlatformGA;

use solideagle\plugins\ga\manageuser;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;
use solideagle\Config;
use solideagle\logging\Logger;
use solideagle\scripts\GlobalUserManager;

class usermanager implements TaskInterface
{
	const ActionAddUser = "AddUser";
	const ActionUpdateUser = "UpdateUser";
	const ActionDelUser = "DelUser";
	const ActionAddUserToOu = "AddUserToOU";
	const ActionSetPhoto = "SetPhoto";
	const ActionUpdatePassword = "UpdatePassword";
	const ActionAddUserToGroup = "AddUserToGroup";
	const ActionRemoveUserFromGroup = "RemoveUserFromGroup";
	const ActionSetEmailSignature = "SetEmailSignature";
	const ActionSetAlias = "SetAlias";

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddUser)
		{
			Logger::log("Trying to add user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);

		
			$ret = manageuser::addUser($config["user"], $config["currentou"],$config["parentous"],$p->isTypeOf(Type::TYPE_LEERLING));

			if($ret->isSucces())
			{
				Logger::log("Successfully created user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
				$platform = new PlatformGA();
				$platform->setPersonId($config["user"]->getId());
				$platform->setEnabled(true);
				PlatformGA::addToPlatform($platform);
				GlobalUserManager::cleanPasswordIfAllAccountsExist($config["user"]->getId());
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionUpdateUser && isset($config["user"]) && isset($config["enabled"]))
		{
			Logger::log("Trying to update user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageuser::updateUser($config["user"], $config["enabled"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully updated user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
				$platform = PlatformGA::getPlatformConfigByPersonId($config["user"]->getId());
				$platform->setEnabled($config["enabled"]);
				PlatformGA::updatePlatform($platform);
				return true;
			}
			else
			{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionDelUser && isset($config["user"]))
		{
			Logger::log("Trying to remove user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageuser::removeUser($config["user"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully removed user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
				PlatformGA::removePlatformByPersonId($config["user"]->getId());
				return true;
			}
			else
			{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionSetPhoto && isset($config["username"]) && isset($config["pictureurl"]))
		{
			Logger::log("Trying to set picture of user \"" . $config["username"] . "\" in Google Apps.",PEAR_LOG_INFO);
			\solideagle\data_access\helpers\imagehelper::downloadTempFile($config["pictureurl"], Config::singleton()->tempstorage . "tempPic");
			$ret = manageuser::setPhoto($config["username"], Config::singleton()->tempstorage . "tempPic");

			if($ret->isSucces())
			{
				Logger::log("Successfully set picture of user \"" . $config["username"] . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else
			{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionUpdatePassword && isset($config["username"]) && isset($config["password"]))
		{
			Logger::log("Trying to update password of user \"" . $config["username"] . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageuser::updatePassword($config["username"], $config["password"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully updated password of user \"" . $config["username"] . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else
			{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveUserFromGroup && isset($config["groupname"]) && isset($config["username"]))
		{
			Logger::log("Trying to remove user \"" . $config["username"] . "\" from group \"" . $config["groupname"] . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageuser::removeUserFromGroup($config["groupname"], $config["username"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully removed user \"" . $config["username"] . "\" from group \"" . $config["groupname"] . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else
			{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}


		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false; //it failed for some reason

	}

	public static function prepareAddUser($person)
	{
		$config["action"] = self::ActionAddUser;
		$config["user"] = $person;
		$config["currentou"] = Group::getGroupById($person->getGroupId());
		$config["parentous"] =  Group::getParents($config["currentou"]);
		$config["groupname"] = Group::getMailAdd($config["currentou"]);

		TaskQueue::insertNewTask($config, $person->getId());

		if ($person->getPictureUrl() != null)
			self::prepareSetPhoto($person);
	}

	/**
	 *
	 *
	 * @param Person $person
	 * @param bool $enabled
	 */
	public static function prepareUpdateUser($person, $oldPerson, $enabled = true)
	{
		$config["action"] = self::ActionUpdateUser;
		$config["user"] = $person;

		$config["enabled"] = $enabled;

		if ($person->getPictureUrl() != null && $person->getPictureUrl() != $oldPerson->getPictureUrl())
			self::prepareSetPhoto($person);

		$platform = PlatformGA::getPlatformConfigByPersonId($person->getId());

		if ($oldPerson->getFirstName() != $person->getFirstName()
				|| $oldPerson->getName() != $person->getName()
				|| $enabled != $platform->getEnabled())
		{
			if($enabled == $platform->getEnabled())
				self::prepareSetEmailSignature($person);

			TaskQueue::insertNewTask($config, $person->getId());
		}
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

	public static function prepareRemoveUserFromGroup($person, $group)
	{
		$config["action"] = self::ActionRemoveUserFromGroup;
		$config["groupname"] = $group->getName();
		$config["username"] = $person->getAccountUsername();

		TaskQueue::insertNewTask($config, $person->getId(), TaskQueue::TypePerson);
	}

}

?>
