<?php
require_once 'data_access/GroupTaskQueue.php';
require_once 'data_access/PersonTaskQueue.php';

use DataAcces\GroupTaskQueue;
use DataAcces\PersonTaskQueue;

//spl_autoload_extensions(".php"); // comma-separated list
//spl_autoload_register();


class deamon
{
	public function __construct()
	{
		$this->runGroupTasks();
		$this->runPersonTasks();
	}

	private function runGroupTasks()
	{
		foreach(GroupTaskQueue::getTasksToRun() as $grouptask)
		{
			$toRun =  "./" . $grouptask->getPath_script() . $grouptask->getTaskname() . ".php";
			
			
			if(file_exists($toRun))
			{
				require_once $toRun;
			}else{
				$grouptask->setErrorMessages("Task script: " .$toRun.  " does not exist!");
				GroupTaskQueue::increaseErrorCount($grouptask);
				continue;
			}
			
			$class = $grouptask->getTaskname();
				
			if(class_exists($class))
			{
				$script = new $class();
			}else{
				$grouptask->setErrorMessages("Task class: " .$class.  " does not exist!");
				PersonTaskQueue::increaseErrorCount($persontask);
				continue;
			}
			
			
			$class = $grouptask->getTaskname();
				
			if(class_exists($class))
			{
				$script = new $class();
			}else{
				$grouptask->setErrorMessages("Task class: " .$class.  " does not exist!");
				GroupTaskQueue::increaseErrorCount($grouptask);
				continue;
			}
				
			if(method_exists($script,"runScript"))
			{
				if($script->runScript($grouptask))
				{
					GroupTaskQueue::addToRollback($grouptask);
				}else{
					GroupTaskQueue::increaseErrorCount($grouptask);
				}
			}else{
				$grouptask->setErrorMessages("Task method: runScript does not exist!");
				GroupTaskQueue::increaseErrorCount($grouptask);
				continue;
			}
			
			
		}
	}
	
	private function runTask($task)
	{
		
	}
	
	
	private function runPersonTasks()
	{
		foreach(PersonTaskQueue::getTasksToRun() as $persontask)
		{
			$toRun =  $persontask->getPath_script() . $persontask->getTaskname() . ".php";
			
			if(file_exists($toRun))
			{
				require_once $toRun;
			}else{
				$persontask->setErrorMessages("Task script: " .$toRun.  " does not exist!");
				PersonTaskQueue::increaseErrorCount($persontask);
				continue;
			}
			
			
			$class = $persontask->getTaskname();
			
			if(class_exists($class))
			{
				$script = new $class();
			}else{
				$persontask->setErrorMessages("Task class: " .$class.  " does not exist!");
				PersonTaskQueue::increaseErrorCount($persontask);
				continue;
			}
			
			if(method_exists($script,"runScript"))
			{
				if($script->runScript($persontask))
				{
					PersonTaskQueue::addToRollback($persontask);
				}else{
					PersonTaskQueue::increaseErrorCount($persontask);
				}
			}else{
				$persontask->setErrorMessages("Task method: runScript does not exist!");
				PersonTaskQueue::increaseErrorCount($persontask);
				continue;
			}
				
			
			
		}
	}
}

new deamon();

?>