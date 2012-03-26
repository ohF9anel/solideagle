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
				//require_once $toRun;
			}else{
				$taskqueue->setErrorMessages("Task script: " .$toRun.  " does not exist!");
				TaskQueue::increaseErrorCount($taskqueue);
				return;
			}
			
			$class = "solideagle\\" . str_replace("/", "\\", "scripts\\" . $taskqueue->getTask()->getPathScript()) .  $taskqueue->getTask()->getName();

                       
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
				}else{
					
					TaskQueue::increaseErrorCount($taskqueue);
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