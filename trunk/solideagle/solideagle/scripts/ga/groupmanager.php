<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Group;

use solideagle\plugins\ga\managegroup;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;
use solideagle\plugins\StatusReport;
use solideagle\Config;
use solideagle\logging\Logger;


class groupmanager implements TaskInterface
{
	const ActionAddGroup = "AddGroup";
	const ActionRemoveGroup = "RemoveGroup";
	const ActionAddGroupToGroup = "AddGroupToGroup";
	const ActionRemoveGroupFromGroup = "RemoveGroupFromGroup";
	const ActionRenameGroup = "RenameGroup";

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddGroup)
		{
			Logger::log("Trying to add group \"" . $config["groupname"] . "\" with mailadd: " . $config["groupmail"] . " in Google Apps.",PEAR_LOG_INFO);

			$ret = managegroup::addGroup($config["groupname"],$config["groupmail"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully added group \"" . $config["groupmail"] . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveGroup)
		{
			Logger::log("Trying to remove group \"" . $config["groupmail"] . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = managegroup::removeGroup($config["groupmail"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully removed group \"" .$config["groupmail"] . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionAddGroupToGroup && isset($config["childgroupname"]) && isset($config["parentgroupname"]))
		{
			Logger::log("Trying to add group \"" . $config["childgroupname"] . "\" to group \"" . $config["parentgroupname"] . "\" in Google Apps.",PEAR_LOG_INFO);

			$ret = managegroup::addGroupToGroup($config["childgroupname"], $config["parentgroupname"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully added group \"" . $config["childgroupname"] . "\" to group \"" . $config["parentgroupname"] . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveGroupFromGroup && isset($config["childgroupname"]) && isset($config["parentgroupname"]))
		{
			Logger::log("Trying to remove group \"" . $config["childgroupname"] . "\" from group \"" . $config["parentgroupname"] . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = managegroup::removeGroupFromGroup($config["childgroupname"], $config["parentgroupname"]);

			if($ret->isSucces())
			{
				Logger::log("Successfully removed group \"" . $config["childgroupname"] . "\" from group \"" . $config["parentgroupname"] . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRenameGroup && isset($config["oldgroupname"]) && isset($config["newgroupname"]))
		{
			$ret = new StatusReport(false,"Deze actie wordt niet ondersteund door GAM. Hernoem de groep \""
					. $config["oldgroupname"] . "\" manueel naar \"" . $config["newgroupname"] . "\" en e-mailadres naar \""
					. \solideagle\data_access\helpers\UnicodeHelper::cleanEmailString($config["newgroupname"])
					. "@" . Config::singleton()->googledomain . "\".");

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

	private static function getMailAdd($group)
	{
		return Group::getMailAdd($group);
	}

	public static function prepareAddGroup($group)
	{
		$config["action"] = self::ActionAddGroup;		
		$config["groupmail"] = self::getMailAdd($group);
		$config["groupname"] = $group->getName();

		TaskQueue::insertNewTask($config, $group->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareRemoveGroup($group)
	{
		$config["action"] = self::ActionRemoveGroup;
		$config["groupmail"] = self::getMailAdd($group);
		
		TaskQueue::insertNewTask($config, $group->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareAddGroupToGroup($parentgroup, $childgroup)
	{
		$config["action"] = self::ActionAddGroupToGroup;
		$config["childgroupname"] = self::getMailAdd($childgroup);
		$config["parentgroupname"] = self::getMailAdd($parentgroup);

		TaskQueue::insertNewTask($config, $childgroup->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareRemoveGroupFromGroup($parentgroup, $childgroup)
	{
		$config["action"] = self::ActionRemoveGroupFromGroup;
		$config["childgroupname"] = self::getMailAdd($childgroup);
		$config["parentgroupname"] = self::getMailAdd($parentgroup);
		
		TaskQueue::insertNewTask($config, $childgroup->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareRenameGroup($oldgroup, $newgroup)
	{
		$config["action"] = self::ActionRenameGroup;
		$config["childgroupname"] = self::getMailAdd($childgroup);
		$config["parentgroupname"] = self::getMailAdd($parentgroup);
		
		TaskQueue::insertNewTask($config, $oldgroup->getId(), TaskQueue::TypeGroup);
	}

}

?>
