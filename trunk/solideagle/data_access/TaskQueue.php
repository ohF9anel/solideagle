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
	//---
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
		$cmd->addParam(":personid", $taskQueue->getGroupid());
		$cmd->addParam(":groupid", $taskQueue->getGroupid());
		$cmd->addParam(":task_id", $taskQueue->getTask()->getId());
		$cmd->addParam(":config", $taskQueue->getConfigurationForDb());

		$cmd->execute();
	}
	
	public static function getTasksToRun()
	{
		
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