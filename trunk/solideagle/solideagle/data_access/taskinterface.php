<?php
namespace solideagle\data_access;

interface TaskInterface
{

	/**
	 *
	 * @param TaskQueue $taskqueue
	 */
	public function runTask($taskqueue);

	public function getParams();

	public function createTaskFromParams($params);


}
?>