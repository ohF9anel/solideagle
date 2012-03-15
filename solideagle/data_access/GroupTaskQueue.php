<?php
namespace DataAcces;
require_once 'database/databasecommand.php';
require_once 'helpers/dateconverter.php';

use Database\DatabaseCommand;

class GroupTaskQueue
{
	private $id;
	private $task_id;
	private $group_id;
	private $configuration;
	private $rollback_configuration;
	private $path_script;
	private $taskname;
	private $errorcount;
	private $errormessages;
	
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


	
	
	public static function getTasksToRun()
	{
		$sql = "SELECT
				`group_task_queue`.`id`,
				`group_task_queue`.`task_id`,
				`group_task_queue`.`group_id`,
				`group_task_queue`.`configuration`,
				`group_task_queue`.`errorcount`,
				`group_task_queue`.`errormessages`,
				`task`.`path_script`,
				`task`.`name`
				FROM `CentralAccountDB`.`group_task_queue`, `CentralAccountDB`.`task` WHERE `group_task_queue`.`task_id` =  `task`.`id`;";
		
		$cmd = new DatabaseCommand($sql);
		
		$retarr = array();
		
		$cmd->executeReader()->readAll(function($row) use (&$retarr){		
			
				$gtq = new GroupTaskQueue();
				
				$gtq->setId($row->id);
				$gtq->setGroup_id($row->group_id);
				$gtq->setErrorcount($row->errorcount);
				$gtq->setConfigurationFromDb($row->configuration);
				$gtq->setErrormessages($row->errormessages);
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
		$cmd->addParam(":configuration", $groupTaskQueue->getConfigurationForDb());
		
		$cmd->execute();
	}
	
	/**
	 *
	 * @param GroupTaskQueue $groupTaskQueue
	 */
	public static function increaseErrorCount($groupTaskQueue)
	{
		$sql = "UPDATE `CentralAccountDB`.`group_task_queue`
				SET
				`errorcount` = `errorcount` + 1,
				`errormessages` = :errormessages
				WHERE  id = :gtqId;";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->BeginTransaction();
		$cmd->addParam(":gtqId", $groupTaskQueue->getId());
		$cmd->addParam(":errormessages", $groupTaskQueue->getErrormessages());
		$cmd->execute();
		$cmd->CommitTransaction();
	}
	
	/**
	 * 
	 * @param GroupTaskQueue $groupTaskQueue
	 */
	public static function addToRollback($groupTaskQueue)
	{
		$sql = "DELETE FROM  `CentralAccountDB`.`group_task_queue` WHERE `group_task_queue`.`id` = :gtqid";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->BeginTransaction();
		
		
		$cmd->addParam(":gtqid", $groupTaskQueue->getId());
		$cmd->execute();
		
		$sql = "INSERT INTO `CentralAccountDB`.`group_task_rollback`
				(
				`task_id`,
				`group_id`,
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
		
		$cmd->addParam(":task_id", $groupTaskQueue->getTask_id());
		$cmd->addParam(":group_id", $groupTaskQueue->getGroup_id());
		$cmd->addParam(":original_configuration", $groupTaskQueue->getConfigurationForDb());
		$cmd->addParam(":rollback_configuration", $groupTaskQueue->getRollback_ConfigurationForDb());
		$cmd->addParam(":task_executed_on", DateConverter::timestampDateToDb(time()));
		$cmd->addParam(":task_executed_by", NULL);
		
		$cmd->execute();
		
		$cmd->CommitTransaction();
		
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
	
	public function setRollback_ConfigurationFromDb($config)
	{
		$this->rollback_configuration = $config;
	}
	
	public function getRollback_ConfigurationForDb()
	{
		return $this->rollback_configuration;
	}
	
	/****************/
	
	public function getConfiguration()
	{
		return unserialize(base64_decode($this->configuration));
	}
	
	public function setConfiguration($configuration)
	{
		$this->configuration =  base64_encode(serialize($configuration));
	}

	public function getRollback_configuration()
	{
		return unserialize(base64_decode($this->rollback_configuration));
	  
	}

	public function setRollback_configuration($rollback_configuration)
	{
		$this->rollback_configuration =  base64_encode(serialize($rollback_configuration));
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
	
	public function setErrorMessages($errormessages)
	{
		$this->errormessages = $errormessages;
	}

	public function addErrormessage($errormessages)
	{
	    $this->errormessages .= $errormessages . "\n";
	}
}