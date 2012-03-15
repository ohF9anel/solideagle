<?php
namespace DataAcces;
require_once 'database/databasecommand.php';

use Database\DatabaseCommand;

class GroupTaskQueue
{
	private $id;
	private $task_id;
	private $group_id;
	private $configuration;
	private $path_script;
	private $taskname;
	
	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	}

	public function getTask_id()
	{
	    return $this->task_id;
	}

	public function setTask_id($task_id)
	{
	    $this->task_id = $task_id;
	}

	public function getGroup_id()
	{
	    return $this->group_id;
	}

	public function setGroup_id($group_id)
	{
	    $this->group_id = $group_id;
	}

	public function getConfiguration()
	{
	    return $this->configuration;
	}

	public function setConfiguration($configuration)
	{
	    $this->configuration = $configuration;
	}
	
	
	public static function getTasksToRun()
	{
		$sql = "SELECT
				`group_task_queue`.`id`,
				`group_task_queue`.`task_id`,
				`group_task_queue`.`group_id`,
				`group_task_queue`.`configuration`,
				`task`.`path_script`,
				`task`.`name`
				FROM `CentralAccountDB`.`group_task_queue`, `CentralAccountDB`.`task` WHERE `group_task_queue`.`task_id` =  `task`.`id`;";
		
		$cmd = new DatabaseCommand($sql);
		
		$retarr = array();
		
		$cmd->executeReader()->readAll(function($row) use (&$retarr){		
			
				$gtq = new GroupTaskQueue();
				
				$gtq->setId($row->id);
				$gtq->setGroup_id($row->group_id);
				$gtq->setConfiguration($row->configuration);
				$gtq->setTask_id($row->task_id);
				$gtq->setPath_script($row->path_script);
				$gtq->setTaskname($row->name);
		
				$retarr[] = $gtq;
				
		});
		
		return $retarr;
	}
	
	public static function addTaskToQueue($groupTaskQueue)
	{
		$sql = "INSERT INTO `CentralAccountDB`.`group_task_queue`
		(
		`group_id`,
		`task_id`,
		`configuration`)
		VALUES
		(
		
		:group_id ,
		:task_id ,
		:configuration
		);";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":group_id", $groupTaskQueue->getGroup_id());
		$cmd->addParam(":task_id", $groupTaskQueue->getTask_id());
		$cmd->addParam(":configuration", $groupTaskQueue->getConfiguration());
		
		$cmd->execute();
	}
	

	public function getPath_script()
	{
	    return $this->path_script;
	}

	public function setPath_script($path_script)
	{
	    $this->path_script = $path_script;
	}

	public function getTaskname()
	{
	    return $this->taskname;
	}

	public function setTaskname($taskname)
	{
	    $this->taskname = $taskname;
	}
}