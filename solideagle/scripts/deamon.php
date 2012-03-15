<?php
require_once 'data_access/GroupTaskQueue.php';

use DataAcces\GroupTaskQueue;



class deamon
{
	public function __construct()
	{
		spl_autoload_register(array($this, 'loader'));
		$this->runGroupTasks();
	}
	
	private function loader($className) {
		include $className . '.php';
	}
	
	private function runGroupTasks()
	{
		foreach(GroupTaskQueue::getTasksToRun() as $grouptask)
		{
			$toRun =  $grouptask->getTaskname();
			$script = new $toRun();
			$script->runScript($grouptask);
		}
	}
}

new deamon();

?>