<?php

namespace DataAcces;
require_once 'database/databasecommand.php';
require_once 'helpers/dateconverter.php';
require_once 'BaseTaskQueue.php';

use Database\DatabaseCommand;

class PersonTaskQueue extends BaseTaskQueue
{

	private $person_id;
	
	public function getPerson_id()
	{
	    return $this->person_id;
	}

	public function setPerson_id($person_id)
	{
	    $this->person_id = $person_id;
	}

	/**
	 * 
	 * Enter description here ...
	 * @param PersonTaskQueue $persontaskqueue
	 */
	public static function addTaskToQueue($persontaskqueue)
	{
		$sql = "INSERT INTO `CentralAccountDB`.`person_task_queue`
				(
				`person_id`,
				`task_id`,
				`configuration`)
				VALUES
				(
				
				:person_id ,
				:task_id ,
				:configuration 
				);";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $persontaskqueue->getPerson_id());
		$cmd->addParam(":task_id", $persontaskqueue->getTask_id());
		$cmd->addParam(":configuration", $persontaskqueue->getConfiguration());
		
		$cmd->execute();
	}
	
	public static function getTasksToRun()
	{
		$sql="SELECT
				`person_task_queue`.`id`,
				`person_task_queue`.`person_id`,
				`person_task_queue`.`task_id`,
				`person_task_queue`.`configuration`,
				`person_task_queue`.`errorcount`,
				`person_task_queue`.`errormessages`,
				`task`.`path_script`,
				`task`.`name`
				FROM `CentralAccountDB`.`person_task_queue`, `CentralAccountDB`.`task` 
				WHERE `person_task_queue`.`task_id`=  `task`.`id`;";
		
		$cmd = new DatabaseCommand($sql);
		
		$retarr = array();
		
		$cmd->executeReader()->readAll(function($row) use (&$retarr){
				
			$ptq = new PersonTaskQueue();
		
			$ptq->setId($row->id);
			$ptq->setPerson_id($row->person_id);
			$ptq->setErrorcount($row->errorcount);
			$ptq->setConfigurationFromDb($row->configuration);
			$ptq->setErrormessages($row->errormessages);
			$ptq->setTask_id($row->task_id);
			$ptq->setPath_script($row->path_script);
			$ptq->setTaskname($row->name);
		
			$retarr[] = $ptq;
		
		});
		
		return $retarr;
	}
	
	/**
	 * 
	 * @param PersonTaskQueue $personTaskQueue
	 */
	public static function increaseErrorCount($personTaskQueue)
	{
		$sql = "UPDATE `CentralAccountDB`.`person_task_queue`
		SET
		`errorcount` = `errorcount` + 1,
		`errormessages` = :errormessages
		WHERE  id = :gtqId;";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->BeginTransaction();
		$cmd->addParam(":gtqId", $personTaskQueue->getId());
		$cmd->addParam(":errormessages", $personTaskQueue->getErrormessages());
		$cmd->execute();
		$cmd->CommitTransaction();
	}
	
	/**
	 *
	 * @param PersonTaskQueue $personTaskQueue
	 */
	public static function addToRollback($personTaskQueue)
	{
		$sql = "DELETE FROM  `CentralAccountDB`.`group_task_queue` WHERE `group_task_queue`.`id` = :gtqid";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->BeginTransaction();
		
		
		$cmd->addParam(":gtqid", $personTaskQueue->getId());
		$cmd->execute();
		
		$sql = "INSERT INTO `CentralAccountDB`.`group_task_rollback`
		(
		`task_id`,
		`person_id`,
		`original_configuration`,
		`rollback_configuration`,
		`task_executed_on`,
		`task_executed_by`)
		VALUES
		(
		:task_id,
		:group_id,
		:original_configuration,
		:rollback_configuration,
		:task_executed_on,
		:task_executed_by
		);";
		
		$cmd->newQuery($sql);
		
		$cmd->addParam(":task_id", $personTaskQueue->getTask_id());
		$cmd->addParam(":person_id", $personTaskQueue->getGroup_id());
		$cmd->addParam(":original_configuration", $personTaskQueue->getConfigurationForDb());
		$cmd->addParam(":rollback_configuration", $personTaskQueue->getRollback_ConfigurationForDb());
		$cmd->addParam(":task_executed_on", DateConverter::timestampDateToDb(time()));
		$cmd->addParam(":task_executed_by", NULL);
		
		$cmd->execute();
		
		$cmd->CommitTransaction();
	}
}


?>