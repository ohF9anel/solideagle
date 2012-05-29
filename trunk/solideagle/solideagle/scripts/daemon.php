<?php
namespace solideagle\scripts;

use solideagle\Config;

use solideagle\plugins\ad\sshpreformatter;

use solideagle\data_access\platforms;

use solideagle\logging\Logger;

use solideagle\data_access\TaskQueue;

class daemon
{
	public function __construct()
	{
		if($this->isCli())
		{
			set_include_path(get_include_path().PATH_SEPARATOR."../../");
			
			spl_autoload_extensions(".php"); // comma-separated list
			spl_autoload_register();
			
			date_default_timezone_set("Europe/Brussels");
			
			$this->startDaemon();
		}else{
			
			echo "Daemon output:<br />\n";
			exec("php " . __FILE__ . " 2>&1");

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

	public static function doNothing()
	{

	}

	private function startDaemon()
	{
		if(!is_dir(Config::singleton()->tempstorage))
		{
			exec("mkdir " . Config::singleton()->tempstorage);
		}
		
		
		Logger::log("Checking for duplicate daemon",PEAR_LOG_INFO,true);
		
		if($this->isDaemonRunning())
		{
			Logger::log("Cannot start, daemon already running!",PEAR_LOG_WARNING);
			echo "Cannot start, daemon already running!\n";
			return;
		}
		
		Logger::log("No lock file, deamon can start\nCreating lock file",PEAR_LOG_INFO,true);
		


		echo shell_exec("touch ". Config::singleton()->tempstorage ."daemon.lock 2>&1");
			
		$this->runTasks();
		
		Logger::log("Daemon finished, removing lock file ",PEAR_LOG_INFO,true);

		echo shell_exec("rm ". Config::singleton()->tempstorage ."daemon.lock 2>&1");
		
	}


	private function isDaemonRunning()
	{
		if(file_exists(Config::singleton()->tempstorage ."daemon.lock"))
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
		Logger::log("Daemon running tasks now.... please wait",PEAR_LOG_INFO,true);
		
		//credits to this ascii art go to Row
		//http://www.asciiworld.com/-Eagles-.html
		$eagle = <<<EOT
		
		
  _________      .__  .__    .______________              .__          
 /   _____/ ____ |  | |__| __| _/\_   _____/____     ____ |  |   ____  
 \_____  \ /  _ \|  | |  |/ __ |  |    __)_\__  \   / ___\|  | _/ __ \ 
 /        (  <_> )  |_|  / /_/ |  |        \/ __ \_/ /_/  >  |_\  ___/ 
/_______  /\____/|____/__\____ | /_______  (____  /\___  /|____/\___  >
        \/                    \/         \/     \//_____/           \/ 

                               /T /I
                              / |/ | .-~/
                          T\ Y  I  |/  /  _
         /T               | \I  |  I  Y.-~/
        I l   /I       T\ |  |  l  |  T  /
     T\ |  \ Y l  /T   | \I  l   \ `  l Y
 __  | \l   \l  \I l __l  l   \   `  _. |
 \ ~-l  `\   `\  \  \ ~\  \   `. .-~   |
  \   ~-. "-.  `  \  ^._ ^. "-.  /  \   |
.--~-._  ~-  `  _  ~-_.-"-." ._ /._ ." ./
 >--.  ~-.   ._  ~>-"    "\   7   7   ]
^.___~"--._    ~-{  .-~ .  `\ Y . /    |
 <__ ~"-.  ~       /_/   \   \I  Y   : |
   ^-.__           ~(_/   \   >._:   | l______
       ^--.,___.-~"  /_/   !  `-.~"--l_ /     ~"-.
              (_/ .  ~(   /'     "~"--,Y   -=b-. _)
               (_/ .  \  :           / l      c"~o \
                \ /    `.    .     .^   \_.-~"~--.  )
                 (_/ .   `  /     /       !       )/
                  / / _.   '.   .':      /        '
                  ~(_/ .   /    _  `  .-<_
                    /_/ . ' .-~" `.  / \  \          ,z=.
                    ~( /   '  :   | K   "-.~-.______//
                      "-,.    l   I/ \_    __{--->._(==.
                       //(     \  <    ~"~"     //
                      /' /\     \  \     ,v=.  ((
                    .^. / /\     "  }__ //===-  `
                   / / ' '  "-.,__ {---(==-
                 .^ '       :  T  ~"   ll       
                / .  .  . : | :!        \
               (_/  /   | | j-"          ~^
                 ~-<_(_.^-~"

EOT;
		//echo $eagle;
		
		foreach(TaskQueue::getAllPlatforms() as $platform)
		//$platform = platforms::PLATFORM_GAPP;
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

						Logger::log("Task " . $taskqueue->getId() ." ran succesfully",PEAR_LOG_DEBUG, true);
							
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
				Logger::log("Running all external batch files!",PEAR_LOG_INFO);
				\solideagle\plugins\ad\sshpreformatter::singleton()->runAllBatchfiles();
			}
		}
	}
}

/*if(isset($_GET["kill"]))
{
	echo("<pre>");
	echo shell_exec("killall php 2>&1") . "\n";
	echo shell_exec("rm daemon.lock 2>&1"). "\n";
	exit("daemon killed</pre>");
}*/

new daemon();

?>
