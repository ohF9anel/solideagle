<?php
require_once 'data_access/TaskQueue.php';

use DataAccess\TaskQueue;


class deamon
{
	public function __construct()
	{
		$this->runTasks();
	}

	private function runTasks()
	{
		echo "<pre>";
                echo("running tasks...\n");
                var_dump(TaskQueue::getTasksToRun());
        echo "</pre>";
                
		foreach(TaskQueue::getTasksToRun() as $taskqueue)
		{
			$class = $taskqueue->getTask()->getName();
			
			$toRun =  "./" . $taskqueue->getTask()->getPathScript() . $class . ".php";
			if(file_exists($toRun))
			{
				require_once $toRun;
			}else{
				$taskqueue->setErrorMessages("Task script: " .$toRun.  " does not exist!");
				TaskQueue::increaseErrorCount($taskqueue);
				continue;
			}
			$class = "\\" . str_replace("/", "", "script\\" . $taskqueue->getTask()->getPathScript()) . "\\" . $taskqueue->getTask()->getName();

                        var_dump($class);
			if(class_exists($class))
			{
				$script = new $class();
			
			}else{
				$taskqueue->setErrorMessages("Task class: " .$class.  " does not exist!");
				TaskQueue::increaseErrorCount($taskqueue);
				continue;
			}
			
			
			if(method_exists($script,"runTask"))
			{
				if($script->runTask($taskqueue))
				{
					TaskQueue::addToRollback($taskqueue);
				}else{
					TaskQueue::increaseErrorCount($taskqueue);
				}
			}else{
				$taskqueue->setErrorMessages("Task method: runScript does not exist!");
				TaskQueue::increaseErrorCount($taskqueue);
				continue;
			}
			
			
		}
	}
	
	
	
}

new deamon();

?>