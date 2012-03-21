<?php

namespace DataAccess;

require_once 'data_access/TaskQueue.php';
use DataAccess\TaskQueue;


abstract class BaseTask
{
	public function getParams();

	private $taskid;

	/**
	 *
	 * @param TaskQueue $taskqueue
	 */
	public function runTask($taskqueue);


	public function __construct($taskid)
	{
		$this->taskid = $taskid;
	}
	
	private function addToQueue($config)
	{
		$tq = new TaskQueue();
		$tq->setConfig($config);
		
		
		TaskQueue::addToQueue($tq);
		
	}
}



?>