<?php
namespace DataAccess;

require_once 'data_access/database/databasecommand.php';
require_once 'data_access/Task.php';
use DataAccess\Task;
use Database\DatabaseCommand;

class TaskQueue
{
	private $id;
	/**
	 *
	 * @var Task
	 */
	private $task;
	private $configuration = array();
	private $errorcount;
	private $errormessages;
	//---only one of these can not be NULL
	private $groupid = NULL;
	private $personid = NULL;

	/**
	 *
	 * @param TaskQueue $taskQueue
	 */
	public static function addToQueue($taskQueue)
	{
		$sql = "INSERT INTO `CentralAccountDB`.`task_queue`
		(
		`person_id`,
		`group_id`,
		`task_id`,
		`configuration`
		)
		VALUES
		(
		:personid,
		:groupid,
		:task_id,
		:config
		);";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":personid", $taskQueue->getPersonid());
		$cmd->addParam(":groupid", $taskQueue->getGroupid());
		$cmd->addParam(":task_id", $taskQueue->getTask()->getId());
		$cmd->addParam(":config", $taskQueue->getConfigurationForDb());

		$cmd->execute();
	}
	
	public static function getTasksToRun()
	{
		$sql = "SELECT
			`task_queue`.`id`,
			`task_queue`.`person_id`,
			`task_queue`.`group_id`,
			`task_queue`.`task_id`,
			`task_queue`.`configuration`,
			`task_queue`.`errorcount`,
			`task_queue`.`errormessages`
			FROM `CentralAccountDB`.`task_queue`;";
		
		$cmd = new DatabaseCommand($sql);
		
		$retarr = array();
		
		$cmd->executeReader()->readAll(function($row) use (&$retarr){
			
			$tq = new TaskQueue();
			$tq->setId($row->id);
			$tq->setPersonid($row->person_id);
			$tq->setGroupid($row->group_id);
			$tq->setTask(Task::getTaskById($row->task_id));
			$tq->setConfigurationFromDb($row->configuration);
			$tq->setErrorcount($row->errorcount);
			$tq->setErrormessages($row->errormessages);
			
			$retarr[] = $tq;
		});
		
		return $retarr;
	}
	
	public static function addToRollback($taskQueue)
	{
		$sql = "DELETE FROM  `CentralAccountDB`.`task_queue` WHERE `task_queue`.`id` = :tqid";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->BeginTransaction();
		
		
		$cmd->addParam(":tqid", $taskQueue->getId());
		$cmd->execute();
		
		$cmd->CommitTransaction();
	}
	
	/**
	 * 
	 * @param TaskQueue $taskQueue
	 */
	public static function increaseErrorCount($taskQueue)
	{
		
	$sql = "UPDATE `CentralAccountDB`.`task_queue`
				SET
				`errorcount` = `errorcount` + 1,
				`errormessages` = :errormessages
				WHERE  id = :tqId;";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->BeginTransaction();
		$cmd->addParam(":tqId", $taskQueue->getId());
		$cmd->addParam(":errormessages", $taskQueue->getErrormessages());
		$cmd->execute();
		$cmd->CommitTransaction();
	}

	public function getGroupid()
	{
	    return $this->groupid;
	}

	public function setGroupid($groupid)
	{
	    $this->groupid = $groupid;
	}

	public function getPersonid()
	{
	    return $this->personid;
	}

	public function setPersonid($personid)
	{
	    $this->personid = $personid;
	}
	
	/********************/
	
	//PHP 5.3 does not support $this or ::self in closures
	//not so pretty workaround
	
	public function setConfigurationFromDb($config)
	{
		$this->configuration = $config;
	}
	
	public function getConfigurationForDb()
	{
		return $this->configuration;
	}

	/****************/
	
	public function getConfiguration()
	{
		return @unserialize(base64_decode($this->configuration));
	}
	
	public function setConfiguration($configuration)
	{
		$this->configuration =  base64_encode(serialize($configuration));
	}
	
	public function getId()
	{
		return $this->id;
	}
	
	public function setId($id)
	{
		$this->id = $id;
	}
	
	public function setTaskId($id)
	{
		$this->task = new Task($id);
	}
	
	/**
	 * 
	 * @return Task
	 */
	public function getTask()
	{
		return $this->task;
	}
	
	public function setTask($task)
	{
		$this->task = $task;
	}
	
	public function getErrorcount()
	{
		return $this->errorcount;
	}
	
	public function setErrorcount($errorcount)
	{
		$this->errorcount = $errorcount;
	}
	
	public function getErrormessages()
	{
		return $this->errormessages;
	}
	
	public function setErrormessages($errormessages)
	{
		$this->errormessages = $errormessages;
	}
}


?>