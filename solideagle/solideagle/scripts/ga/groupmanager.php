<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Group;

use solideagle\plugins\ga\managegroup;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class groupmanager implements TaskInterface
{
	const ActionAddGroup = 0;
	const ActionRemoveGroup = 1;

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddGroup && isset($config["group"]))
		{
			$ret = managegroup::addGroup($config["group"]);

			if($ret->isSucces())
				return true;
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveGroup && isset($config["group"]))
		{
			$ret = managegroup::removeGroup($config["group"]);

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



	public static function prepareAddGroup($group)
	{
		$config["action"] = self::ActionAddGroup;
		$config["group"] = $group;

		TaskQueue::insertNewTask($config, $group->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareRemoveGroup($group)
	{
		$config["action"] = self::ActionRemoveGroup;
		$config["group"] = $group;

		TaskQueue::insertNewTask($config, $group->getId(), TaskQueue::TypeGroup);
	}

}

?>
