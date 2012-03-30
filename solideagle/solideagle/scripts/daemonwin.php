<?php
namespace solideagle\scripts;

use solideagle\data_access\TaskQueue;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

class daemon
{
	public function __construct()
	{
		if($this->isCli())
		{
			$this->runTasks();
		}
	}
	
	private function isCli() {
		return php_sapi_name()==="cli";
	}

	private function runTasks()
	{
		
        echo("running " . count(TaskQueue::getTasksToRun()) . " tasks...\n");

     
		foreach(TaskQueue::getTasksToRun() as $taskqueue)
		{
         
			$class = $taskqueue->getTask_class();
			
			echo "Running task: " . $class . "\n";
			var_dump($taskqueue);
			flush();
			
			if(class_exists($class))
			{
				$script = new $class();
			
			}else{
				$taskqueue->setErrorMessages("Task class: " .$class.  " does not exist!");
				TaskQueue::increaseErrorCount($taskqueue);
				return;
			}
			
			
			if(method_exists($script,"runTask"))
			{
				if($script->runTask($taskqueue))
				{
					TaskQueue::addToRollback($taskqueue);
					
					echo("Task ran succesfully!\n");
					
				}else{
					
					TaskQueue::increaseErrorCount($taskqueue);
					
					echo("Task failed\n");
					
					return;
				}
			}else{
				$taskqueue->setErrorMessages("Task method: runScript does not exist!");
				TaskQueue::increaseErrorCount($taskqueue);
				return;
			}
			
		}
	}
}


new daemon();

?>