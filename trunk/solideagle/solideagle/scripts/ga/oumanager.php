<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Group;

use solideagle\plugins\ga\manageou;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class oumanager implements TaskInterface
{
	const ActionAddOu = 0;
	const ActionMoveOu = 1;
	const ActionUpdateOu = 2;
	const ActionRemoveOu = 3;

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddOu && isset($config["ou"]) && isset($config["parentous"]))
		{
			$ret = manageou::addOu($config["ou"], $config["parentous"]);

			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionMoveOu && isset($config["ou"]) && isset($config["oldparents"]) && isset($config["newparents"]))
		{
			$ret = manageou::moveOu($config["ou"], $config["oldparents"], $config["newparents"]);

			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionUpdateOu && isset($config["oldou"]) && isset($config["newou"]) && isset($config["parentous"]))
		{
			$ret = manageou::updateOU($config["oldou"], $config["newou"], $config["parentous"]);

			if($ret->isSucces())
			{
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveOu && isset($config["ou"]) && isset($config["parentous"]))
		{
			$ret = manageou::removeOU($config["ou"], $config["parentous"]);

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



	public static function prepareAddOu($parents,$ou)
	{
		$config["action"] = self::ActionAddOu;
		$config["ou"] = $ou;
		$config["parentous"] = $parents;

		TaskQueue::insertNewTask($config, $ou->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareMoveOu($newparents,$ou, $oldparents)
	{
		$config["action"] = self::ActionMoveOu;
		$config["ou"] = $ou;
		$config["oldparents"] = $oldparents;
		$config["newparents"] = $newparents;

		TaskQueue::insertNewTask($config, $ou->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareUpdateOu($parents,$oldou, $newou)
	{
		$config["action"] = self::ActionUpdateOu;
		$config["oldou"] = $oldou;
		$config["newou"] = $newou;
		$config["parentous"] = $parents;

		TaskQueue::insertNewTask($config, $oldou->getId(), TaskQueue::TypeGroup);
	}

	public static function prepareRemoveOu($parents,$ou)
	{
		$config["action"] = self::ActionRemoveOu;
		$config["ou"] = $ou;
		$config["parentous"] = $parents;

		TaskQueue::insertNewTask($config, $ou->getId(), TaskQueue::TypeGroup);
	}



}

?>
