<?php

namespace solideagle\data_access;

	use solideagle\data_access\database\DatabaseCommand;

class TaskInserter
{	
	
	private static $TASKINSERTER_ENABLED = true;
	
	//impl
	const TypeGroup = 0;
	const TypePerson = 1;
	
	private $taskid;
	//---only one of these can not be NULL
	private $personid = NULL;
	private $groupid = NULL;
	
	public function __construct($taskid,$personOrGroupId,$type)
	{
		$this->taskid = $taskid;
		if($type == self::TypeGroup)
		{
			$this->groupid = $personOrGroupId;
		}elseif ($type == self::TypePerson)
		{
			$this->personid = $personOrGroupId;
		}
	}
	
	public function addToQueue($config)
	{
		
		if(!self::$TASKINSERTER_ENABLED)
		{
			return;
		}
		
		$tq = new TaskQueue();
		$tq->setTaskId($this->taskid);
		$tq->setConfiguration($config);
		$tq->setGroupid($this->groupid);
		$tq->setPersonid($this->personid);	
		TaskQueue::addToQueue($tq);
	}

	public function getTaskid()
	{
	    return $this->taskid;
	}

	public function getPersonid()
	{
	    return $this->personid;
	}

	public function getGroupid()
	{
	    return $this->groupid;
	}
}




?>