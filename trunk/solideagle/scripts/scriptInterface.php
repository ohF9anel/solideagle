<?php


interface scriptInterface
{
	
	public function getParams();
	
	/**
	 * 
	 * @param BaseTaskQueue $taskqueue
	 * 
	 * return true when succes, false when fail
	 */
	public function runScript($taskqueue);
	
	
}


?>