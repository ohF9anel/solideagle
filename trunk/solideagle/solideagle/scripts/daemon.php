<?php
namespace solideagle\scripts;

use solideagle\plugins\ad\sshpreformatter;

use solideagle\data_access\platforms;

use solideagle\logging\Logger;

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
			$this->startDaemon();
		}else{
			exec(sprintf("%s > %s 2>&1 & echo $! >> %s", "php /var/www/solideagle/solideagle/scripts/daemon.php", "/var/www/solideagle/solideagle/scripts/daemon.out", "/var/www/solideagle/solideagle/scripts/daemon.pid"));
				
			/*set_time_limit(60);
			 echo "Running from command line! Output will stop after 60 seconds or when all tasks have been run";

			exec(sprintf("%s > %s 2>&1 & echo $! >> %s", "php daemon.php", "daemon.out", "daemon.pid"));


			$descriptorspec = array(
					0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
					1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
					2 => array("pipe", "a") // stderr is a file to write to
			);

			echo shell_exec("touch daemon.out 2>&1");

			$p = proc_open("tail -f daemon.out",$descriptorspec,$pipes);


			echo "<pre>";

			$buffer = "";

			while(true)
			{
			if(($buffer = fgets($pipes[1])) === false)
			{
			echo "error\n";
			ob_flush();
			flush();
			//break;
			}
				
			if(strpos($buffer,"SEENDBUFFER") !== false)
			{
			break;
			}
			echo $buffer;
			ob_flush();
			flush();
			}

			foreach ($pipes as $pipe)
				fclose($pipe);

			proc_terminate($p);
			proc_close($p);

			echo "daemon ended\n";


			echo "</pre>";

			ob_flush();
			flush();*/

		}
	}

	private function startDaemon()
	{
		if($this->isDaemonRunning())
		{
			echo "Cannot start, daemon already running...\n";
			return;
		}

		echo shell_exec("touch daemon.lock 2>&1");
			
		$this->runTasks();

		echo shell_exec("rm daemon.lock 2>&1");
	}


	private function isDaemonRunning()
	{
		if(file_exists("daemon.lock"))
		{
			return true;
		}
		return false;
	}


	private function isCli() {
		return php_sapi_name()==="cli";
	}

	private function runTasks()
	{
		
		foreach(TaskQueue::getAllPlatforms() as $platform)
		//$platform = platforms::PLATFORM_SMARTSCHOOL;
		{
			$tasksss = TaskQueue::getTasksToRunForPlatform($platform);
			Logger::log("Platform " . $platform . " has " . count($tasksss) . " tasks in queue...",PEAR_LOG_INFO, true);
				
			foreach($tasksss as $taskqueue)
			{
				$conf = $taskqueue->getConfiguration();
				$class = $taskqueue->getTask_class();
					
				if(class_exists($class))
				{
					$script = new $class();
				}else{
					$taskqueue->setErrorMessages("Task class: " .$class.  " does not exist!");
					TaskQueue::increaseErrorCount($taskqueue);
					break;
				}
					
				if(method_exists($script,"runTask"))
				{
					
					Logger::log("Running task with id: " . $taskqueue->getId(),PEAR_LOG_DEBUG, true);
					
					if($script->runTask($taskqueue))
					{
						TaskQueue::addToRollback($taskqueue);
						$conf = $taskqueue->getConfiguration();
						
						Logger::log("Task with id: " . $taskqueue->getId() ." ran succesfull",PEAR_LOG_DEBUG, true);
							
					}else{
						Logger::log("Task: " . $class . " failed with error:\n". $taskqueue->getErrormessages() . "\n"
								. "Task ID: ". $taskqueue->getId() . "\n"
								. "Config: " . var_export($taskqueue->getConfiguration(), true) . "\n", PEAR_LOG_ERR, true);

						TaskQueue::increaseErrorCount($taskqueue);

						break;
					}
				}else{
					$taskqueue->setErrorMessages("Task method: runScript does not exist!");
					TaskQueue::increaseErrorCount($taskqueue);
						
					break;
				}
					
			}
			
			if($platform == platforms::PLATFORM_AD)
			{
				//run all batch files for AD
				\solideagle\plugins\ad\sshpreformatter::singleton()->runAllBatchfiles();
			}
		}
	}
}

if(isset($_GET["kill"]))
{
	echo("<pre>");
	echo shell_exec("killall php 2>&1") . "\n";
	echo shell_exec("rm daemon.lock 2>&1"). "\n";
	exit("daemon killed</pre>");
}

new daemon();

?>
