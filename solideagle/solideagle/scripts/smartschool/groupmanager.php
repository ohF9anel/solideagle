<?php
namespace solideagle\scripts\smartschool;


use solideagle\logging\Logger;

use solideagle\plugins\StatusReport;

use solideagle\plugins\smartschool\data_access\ClassGroup;

use solideagle\data_access\TaskQueue;

use solideagle\data_access\TaskInterface;

class groupmanager implements TaskInterface
{
	const ActionAdd = "AddGroup";
	const ActionMove = "MoveGroup";
	const ActionModify = "ModifyGroup";
	const ActionRemove = "RemoveGroup";

	/**
	 * (non-PHPdoc)
	 * @see solideagle\data_access.TaskInterface::runTask()
	 */
	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config->action == self::ActionAdd)
		{
			Logger::log("Trying to create group: " .$config->newgroup->getName() ." on smartschool",PEAR_LOG_INFO);
			
			$classGroup = new ClassGroup();
			$classGroup->setName($config->newgroup->getName());

			//do not put ANY weird characters in desc, because smartschool does not handle it properly and smartschool WILL break
			//(it does allow html injection... yay!)
			//I did send a bugreport so I hope they fix this
			$classGroup->setDesc("Gemaakt door SolidEagle");
			
			if($config->newgroup->getAdministrativeNumber() !== NULL)
			{
				Logger::log("Group: " .$config->newgroup->getName() ." is an official class with instituenumber: " .
						$config->newgroup->getInstituteNumber() . " and adminnr: " . $config->newgroup->getAdministrativeNumber()  ,PEAR_LOG_INFO);
			}
			
			$classGroup->setAdminNumber($config->newgroup->getAdministrativeNumber());
			$classGroup->setInstituteNumber($config->newgroup->getInstituteNumber());
			
			if(isset($config->parents) && count($config->parents) > 0)
			{
				$classGroup->setParentCode($config->parents[0]->getName());
			}

			$ret = ClassGroup::saveClassGroup($classGroup);
			
			if($ret->isSucces())
			{
				Logger::log("Group: " .$config->newgroup->getName() ." succesfully created on smartschool",PEAR_LOG_INFO);
				return true;
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
			
		}else if($config->action == self::ActionMove)
		{
			Logger::log("Trying to move group: " .$config->group->getName() ." on smartschool from " .
					 $config->oldparents[0]->getName() . " to " . $config->newparents[0]->getName(),PEAR_LOG_INFO);
				
			$ret = new StatusReport(false,"Deze actie wordt niet ondersteund door smartschool. Voer de verplaatsing manueel uit");
				
			if($ret->isSucces())
			{
				Logger::log("Group: " . $config->group->getName()
                        . " succesfully moved on smartschool",PEAR_LOG_INFO);
				return true;
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config->action == self::ActionModify)
		{
			$ret = new StatusReport(false,"Deze actie wordt niet ondersteund door smartschool. Voer de hernoeming manueel uit\n Hernoeming van " 
					. $config->oldgroup->getName() . " naar " . $config->newgroup->getName());
			
			if($ret->isSucces())
			{
				return true;
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config->action == self::ActionRemove)
		{
	
			Logger::log("Trying to remove group: " .$config->group->getName() ." on smartschool",PEAR_LOG_INFO);
			
			$ret = ClassGroup::deleteClassGroupByCode($config->group->getName());
			if($ret->isSucces())
			{
				Logger::log("Group: " .$config->group->getName() ." succesfully removed from smartschool",PEAR_LOG_INFO);
				return true;
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
	}


	/**
	 *
	 * @param array(Group) $parents
	 * @param Group $newgroup
	 */
	public static function prepareAddGroup($parents,$newgroup)
	{
		$stdConfig = new \stdClass();
		$stdConfig->action = self::ActionAdd;

		$stdConfig->parents = $parents;
		$stdConfig->newgroup = $newgroup;

		TaskQueue::insertNewTask($stdConfig, $newgroup->getId(),TaskQueue::TypeGroup);
	}

	public static function prepareModifyGroup($parents,$oldgroup,$newgroup)
	{
		$stdConfig = new \stdClass();
		$stdConfig->action = self::ActionModify;

		$stdConfig->parents = $parents;
		$stdConfig->newgroup = $newgroup;
		$stdConfig->oldgroup = $oldgroup;

		TaskQueue::insertNewTask($stdConfig, $newgroup->getId(),TaskQueue::TypeGroup);
	}

	public static function prepareRemoveGroup($parents,$group)
	{
		$stdConfig = new \stdClass();
		$stdConfig->action = self::ActionRemove;

		//don't need the parents for ss
		//$stdConfig->parents = $parents;
		$stdConfig->group = $group;

		TaskQueue::insertNewTask($stdConfig, $group->getId(),TaskQueue::TypeGroup);
	}

	public static function prepareMoveGroup($oldparents,$newparents,$group)
	{
		$stdConfig = new \stdClass();
		$stdConfig->action = self::ActionMove;

		$stdConfig->oldparents = $oldparents;
		$stdConfig->newparents = $newparents;
		$stdConfig->group = $group;

		TaskQueue::insertNewTask($stdConfig, $group->getId(),TaskQueue::TypeGroup);
	}
}