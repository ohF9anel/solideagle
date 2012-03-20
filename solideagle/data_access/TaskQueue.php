<?php
namespace DataAcces;

require_once 'data_access/Task.php';

class TaskQueue
{
	private $id;
	/**
	 *
	 * @var Task
	 */
	private $task;
	private $configuration;
	private $errorcount;
	private $errormessages;
	
	private $groupid;
	private $personid;

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getTask()
	{
		return $this->task;
	}

	public function setTask($task)
	{
		$this->task = $task;
	}

	public function getConfiguration()
	{
		return $this->configuration;
	}

	public function setConfiguration($configuration)
	{
		$this->configuration = $configuration;
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

	/**
	 *
	 * @param TaskQueue $taskQueue
	 */
	public static function addTaskToQueue($taskQueue)
	{
		$sql = "INSERT INTO `CentralAccountDB`.`task_queue`
		(
		`person_id`,
		`group_id`,
		`task_id`,
		`configuration`,
		)
		VALUES
		(
		:personid,
		:groupid,
		:task_id,
		:config
		);";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":personid", $taskQueue->getg);
		$cmd->addParam(":groupid", $groupTaskQueue->getTask_id());
		$cmd->addParam(":task_id", $groupTaskQueue->getConfigurationForDb());
		$cmd->addParam(":config", $groupTaskQueue->getConfigurationForDb());

		$cmd->execute();
	}
}


?>