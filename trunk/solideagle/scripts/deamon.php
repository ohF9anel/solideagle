<?php
require_once 'data_access/GroupTaskQueue.php';

use DataAcces\GroupTaskQueue;

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


class deamon
{
	public function __construct()
	{
		//spl_autoload_register(array($this, 'loader'));
		$this->runGroupTasks();
	}
	
	private function loader($className) {
		require_once $className . '.php';
	}
	
	private function runGroupTasks()
	{
		foreach(GroupTaskQueue::getTasksToRun() as $grouptask)
		{
			$toRun =  $grouptask->getPath_script() . $grouptask->getTaskname();
			$script = new $toRun();
			
			if($script->runScript($grouptask))
			{
				GroupTaskQueue::addToRollback($grouptask);	
			}
		}
	}
}

new deamon();

?>