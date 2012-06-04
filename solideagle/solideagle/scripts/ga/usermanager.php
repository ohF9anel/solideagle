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
	const ActionMoveUser = "MoveUser";
	const ActionSetPhoto = "SetPhoto";
	const ActionUpdatePassword = "UpdatePassword";
	const ActionEnableDisableAccount = "EnableDisableAccount";

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddUser)
		{
			Logger::log("Trying to add user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageuser::addUser($config["user"], $config["currentou"],$config["parentous"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully created user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
				GlobalUserManager::cleanPasswordIfAllAccountsExist($config["user"]->getId());
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionEnableDisableAccount)
		{
			Logger::log("Trying to set account status of user \"" . $config["user"]->getAccountUsername() . "\" to '" . $config["enabled"]
					. "' in Google Apps.",PEAR_LOG_INFO);
			
			$ret = manageuser::EnableDisableAccount($config["user"], $config["enabled"]);
		
			if($ret->isSucces())
			{
				Logger::log("Successfully set account status of user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
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
		else if($config["action"] == self::ActionUpdateUser)
		{
			Logger::log("Trying to update user \"" . $config["user"]->getAccountUsername() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageuser::enableDisableUser($config["user"], $config["enabled"]);

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
		else if($config["action"] == self::ActionDelUser)
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
		else if($config["action"] == self::ActionSetPhoto)
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
		else if($config["action"] == self::ActionUpdatePassword)
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
		else if($config["action"] == self::ActionMoveUser)
		{
			Logger::log("Trying to move user \"" . $config["person"]->getAccountUserName() .
					"\" from group \"" . $config["olgdgroupname"] .
					"\" to group \"" . Group::getMailAdd($config["newgroup"]) .
					"\" in Google Apps.",PEAR_LOG_INFO);

			$ret = manageuser::moveUser($config["person"], $config["mailalias"], $config["olgdgroupname"], $config["newgroup"],$config["parentous"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully moved user \"" . $config["person"]->getAccountUserName() . "\" from group \"" 
						. $config["olgdgroupname"] . "\" to\"" 
						. Group::getMailAdd($config["newgroup"]) . "\" in Google Apps.",PEAR_LOG_INFO);
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

	
	/*public static function prepareUpdateUser($person, $oldPerson, $enabled = true)
	{
		$config["action"] = self::ActionUpdateUser;
		$config["user"] = $person;

		if ($person->getPictureUrl() != null && $person->getPictureUrl() != $oldPerson->getPictureUrl())
		{
			self::prepareSetPhoto($person);
		}

		$platform = PlatformGA::getPlatformConfigByPersonId($person->getId());
		TaskQueue::insertNewTask($config, $person->getId());
	}*/

	public static function prepareEnableDisableAccount($person,$enabled)
	{
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

	public static function prepareSetPhoto($person)
	{
		$config["action"] = self::ActionSetPhoto;
		$config["username"] = $person->getAccountUsername();
		$config["pictureurl"] = $person->getPictureUrl();
		TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareMoveUser($person, $newgroup, $oldgroup)
	{
		$config["action"] = self::ActionMoveUser;
		$config["person"] = $person;
		$config["newgroup"] =$newgroup;
		$config["parentous"] =  Group::getParents($config["newgroup"]);
		$config["mailalias"] = PlatformGA::getPlatformConfigByPersonId($person->getId())->getAliasmail();
		$config["olgdgroupname"] =Group::getMailAdd($oldgroup);

		TaskQueue::insertNewTask($config, $person->getId());
	}


}

?>
