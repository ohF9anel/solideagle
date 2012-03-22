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
	public abstract function runTask($taskqueue);
	
	public abstract function getParams();
	
	public abstract function createTaskFromParams($params);
	
	//impl
	const TypeGroup = 0;
	const TypePerson = 1;
	
	private $taskid;
	//---only one of these can not be NULL
	private $personid = NULL;
	private $groupid = NULL;
	
	protected function __construct($taskid,$personOrGroupId,$type)
	{
		$this->taskid = $taskid;
		if($type == BaseTask::TypeGroup)
		{
			$this->groupid = $personOrGroupId;
		}elseif ($type == BaseTask::TypePerson)
		{
			$this->personid = $personOrGroupId;
		}
	}
	
	protected function addToQueue($config)
	{
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