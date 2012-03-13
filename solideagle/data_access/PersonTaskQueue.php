<?php


namespace DataAccess;

use Database\DatabaseCommand;

class PersonTaskQueue
{
	private $id;
	private $person_id;
	private $task_id;
	private $configuration;
	
	

	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	}

	public function getPerson_id()
	{
	    return $this->person_id;
	}

	public function setPerson_id($person_id)
	{
	    $this->person_id = $person_id;
	}

	public function getTask_id()
	{
	    return $this->task_id;
	}

	public function setTask_id($task_id)
	{
	    $this->task_id = $task_id;
	}

	public function getConfiguration()
	{
	    return $this->configuration;
	}

	public function setConfiguration($configuration)
	{
	    $this->configuration = $configuration;
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
	
}


?>