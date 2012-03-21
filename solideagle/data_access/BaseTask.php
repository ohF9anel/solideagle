<?php

namespace DataAccess;

require_once 'data_access/TaskQueue.php';
use DataAccess\TaskQueue;


abstract class BaseTask
{
	
	/**
	 *
	 * @param TaskQueue $taskqueue
	 */
	public function runTask($taskqueue);
	
	public function getParams();
	
	
	//impl
	
	const TypeGroup = 0;
	const TypePerson = 1;
	
	private $taskid;
	private $personid = NULL;
	private $groupid = NULL;
	
	public function __construct($taskid,$personOrGroupId,$type)
	{
		$this->taskid = $taskid;
		if($type == BaseTask::TypeGroup)
		{
			$this->groupid = $personOrGroupId;
		}elseif ($type == BaseTask::TypeGroup)
		{
			$this->personid = $personOrGroupId;
		}
	}
	
	protected function addToQueue($config)
	{
		$tq = new TaskQueue();
		$tq->setTaskId($this->taskid);
		$tq->setConfig($config);
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