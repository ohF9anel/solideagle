<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Group;

use solideagle\plugins\ga\manageou;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;
use solideagle\logging\Logger;


class oumanager implements TaskInterface
{
	const ActionAddOu = "AddOU";
	const ActionMoveOu = "MoveOU";
	const ActionUpdateOu = "UpdateOU";
	const ActionRemoveOu = "RemoveOU";

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddOu && isset($config["ou"]) && isset($config["parentous"]))
		{
                        Logger::log("Trying to add OU \"" . $config["ou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageou::addOu($config["ou"], $config["parentous"]);
                        
			if($ret->isSucces())
			{
                                Logger::log("Successfully added OU \"" . $config["ou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionMoveOu && isset($config["ou"]) && isset($config["oldparents"]) && isset($config["newparents"]))
		{
                        Logger::log("Trying to move OU \"" . $config["ou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageou::moveOu($config["ou"], $config["oldparents"], $config["newparents"]);

			if($ret->isSucces())
			{
                                Logger::log("Successfully moved OU \"" . $config["ou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionUpdateOu && isset($config["oldou"]) && isset($config["newou"]) && isset($config["parentous"]))
		{
                        Logger::log("Trying to update OU \"" . $config["oldou"]->getName() . "\" to \"" . $config["newou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageou::updateOU($config["oldou"], $config["newou"], $config["parentous"]);

			if($ret->isSucces())
			{
                                Logger::log("Successfully updated OU \"" . $config["oldou"]->getName() . "\" to \"" . $config["newou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
				return true;
			}
			else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionRemoveOu && isset($config["ou"]) && isset($config["parentous"]))
		{
                        Logger::log("Trying to remove OU \"" . $config["ou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
			$ret = manageou::removeOU($config["ou"], $config["parentous"]);

			if($ret->isSucces())
			{
                                Logger::log("Successfully removed OU \"" . $config["ou"]->getName() . "\" in Google Apps.",PEAR_LOG_INFO);
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
