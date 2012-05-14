<?php

namespace solideagle\plugins\ad;
use solideagle\Config;

use solideagle\logging\Logger;

class SSHManager
{
	private  $_connections = array();
	private static $instance;

	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}

	public function getConnection($servername)
	{
		if(isset($this->_connections[$servername]))
		{
			return $this->_connections[$servername];
		}

		Logger::log("Opening new SSH connection to: " . $servername . " with user: " . Config::singleton()->ad_administrator,PEAR_LOG_INFO);

		$conn = new sshconn($servername);
                
		if (!$conn->login(Config::singleton()->ad_administrator, Config::singleton()->ad_password))
		{
			Logger::log("Login to SSH failed on " . $servername);
			return null;
		}

		Logger::log("Login succes on: " . $servername,PEAR_LOG_INFO);

		$this->_connections[$servername] = $conn;

		return $conn;
	}
	
	public function __destruct()
	{
		foreach($this->_connections as $conn)
		{
			$conn->exitShell();
		}
	}
}

class sshconn
{

	private $pipes;
	private $handle;
	private $server;

	public function __construct($server)
	{
		$this->server = $server;
	}

	public function write($cmd)
	{
		fwrite($this->pipes[0], $cmd);
		
	/*	stream_set_blocking($this->pipes[1],0); //unblock streams
		
		while (true)
		{
			$buffer = fgets($this->pipes[1]); //only read 10 to speed it up
			
			if($buffer && strlen($buffer))
			{
				echo "BUFF: " .$buffer;
				echo "CMD: " .$cmd;
			}
			
			
			
			if(strpos($buffer, $cmd)) //waittill command is echod
				break;
		}
		
		//stream_set_blocking($this->pipes[1],1); //block streams
		

		echo "endwrite";*/
		
	}
	
	/*public function readLine()
	{
		while (true)//wait for command to execute
		{
				$buffer = fgets($this->pipes[1]);

				if($buffer !== false) 
					break;
			
				usleep(10); 
		}
		
	
		
		return $buffer;

	}*/

	//call this only after all commandos have been executed!
	public function read()
	{

		$buffer = "";
		$errbuf = "";
		
		$retval = "";
		
		while (($buffer = fgets($this->pipes[1])) != NULL
				/*|| ($errbuf = fgets($this->pipes[2])) != NULL*/) {
			 
			if (strlen($buffer))
			{
				$retval .= $buffer;
				
			}
			/*if (strlen($errbuf))
			{
				echo "ERR: " . $errbuf ;
				@ob_flush();
				@flush();
			}*/
		}
			
		return $retval;
		
	}

	public function login($user,$pass)
	{
		$descriptorspec = array(
				0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
				1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
				2 => array("pipe", "w")   // stderr is a pipe to write to
		);
			
		$commandToExecute = "plink -pw " . $pass .  " " . $user . "@" . $this->server;
		//echo $commandToExecute;

		$p = proc_open($commandToExecute ,$descriptorspec,$pipes);
			
		$this->pipes = $pipes;
		$this->handle = $p;
		
		stream_set_blocking($pipes[1],0); //unblock streams
			
		if(!is_resource($p))
		{
			Logger::log("Failed to start the SSH client locally");
			$this->endConn();
			return false;
		}
		
		fwrite($pipes[0],"y\n");
		
		while (true)	
		{
			$buffer = fgets($this->pipes[1]);
			if(strpos($buffer, "@") !== false) //waittill shell is loaded
				break;
		}
		
		/*echo "Shell Loaded" . "\n" ;
		@ob_flush();
		@flush();*/
		
		fwrite($pipes[0],"cmd\n"); //start cmd
		
		while (true)
		{
			$buffer = fgets($this->pipes[1]);
			if(strpos($buffer, ">") !== false) //waittill cmd is loaded
				break;
		}
		
		/*echo "Cmd Loaded" . "\n" ;
		@ob_flush();
		@flush();*/
		
		
		stream_set_blocking($this->pipes[1],1); //block streams

		return true;
	}
	
	// never call this manually!
	public function exitShell()
	{
		$this->write("exit\nexit\n"); //exit shell
        Logger::log($this->read(), PEAR_LOG_INFO);
		self::endConn();
	}

	private function endConn()
	{
		
		foreach ($this->pipes as $pipe)
			fclose($pipe);

		proc_close($this->handle);

		Logger::log("Closed SSH connection to: " . $this->server ,PEAR_LOG_INFO);
	}

	/*public function __destruct(){
		
		foreach ($this->pipes as $pipe)
			fclose($pipe);

		proc_close($this->handle);
	}*/
}


?>