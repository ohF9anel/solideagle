<?php
namespace solideagle\plugins\ad;

use solideagle\logging\Logger;


use solideagle\Config;

class sshpreformatter
{
	private  $_batchfiles = array();
	private static $instance;


	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
			
			@mkdir(Config::singleton()->batchscriptsdir);
			
			Logger::log("Cleaning batch script directory",PEAR_LOG_INFO);
			//clean batch dir
			@exec("rm " . Config::singleton()->batchscriptsdir . "*.batch"); 
			//create plink yes script
			exec('echo "y\n" > ' . Config::singleton()->batchscriptsdir . "plink.yes.batch");
			exec('echo "cmd /C C:/solideagle.cmd\nexit\nexit\n" > ' . Config::singleton()->batchscriptsdir . "plink.execute.batch");
		}
		return self::$instance;
	}


	public function getFileForServer($servername)
	{
		if(isset($this->_batchfiles[$servername]))
		{
			return $this->_batchfiles[$servername];
		}

		Logger::log("Creating new batch file for " . $servername,PEAR_LOG_INFO);

		$batchfile = new batchfile($servername);

		if(!$batchfile)
		{
			Logger::log("Batchfile could not be created!",PEAR_LOG_INFO);
			trigger_error("Batchfile could not be created!",E_ERROR);
			return null;
		}

		$this->_batchfiles[$servername] = $batchfile;

		return $batchfile;
	}

	public function runAllBatchfiles()
	{
		foreach($this->_batchfiles as $servername => $batchfile)
		{
			$batchfile->closeFile();
				
			sshrunner::executeSSHBatchScript(
					Config::singleton()->ad_administrator,
					Config::singleton()->ad_password,
					$servername,
					$batchfile->getFilePath());
		}
	}
}

class sshrunner
{

	
	public static function executeSSHBatchScript($username,$password,$server,$pathtofile)
	{
		Logger::log("Copying batch file to: " . $server ,PEAR_LOG_INFO);
		
		$copyCommand = "pscp -pw " . $password .  " " . $pathtofile . " " . $username . "@" . $server . ":/cygdrive/c/solideagle.cmd";
		
		exec($copyCommand);
		
		Logger::log("Running batch file for: " . $server ,PEAR_LOG_INFO);

		$commandToExecute = "plink -m " . Config::singleton()->batchscriptsdir . "plink.execute.batch" . " -pw " . $password .  " " . $username . "@" . $server . " < " . Config::singleton()->batchscriptsdir . "plink.yes.batch" . " 2>&1";
		
		$outputarr = array();

		//exec("cat " . $pathtofile,$outputarr);

		//Logger::log("Batchfile input:\n" . implode("\n",$outputarr),PEAR_LOG_INFO);

		$outputarr = array();

		Logger::log("Opening SSH connection to: " . $server ,PEAR_LOG_INFO);

		exec($commandToExecute,$outputarr);

		Logger::log("Batchfile output:\n" . implode("\n",$outputarr) ,PEAR_LOG_INFO);

		Logger::log("Closed SSH connection to: " . $server ,PEAR_LOG_INFO);

		return true;
	}
}

class batchfile
{
	private $batchfile;
	private $path;
	private $isOpenForWriting = false;
	public function __construct($servername)
	{
		$this->path = Config::singleton()->batchscriptsdir . $servername . ".batch";

		@mkdir(Config::singleton()->batchscriptsdir);

		$this->batchfile = fopen($this->path,"a");

		if($this->batchfile)
		{
			$this->isOpenForWriting = true;
			$this->writeToFile("y\n");
			
		}

		return $this->isOpenForWriting;
	}

	//backwards compatibility with sshmanager
	public function write($data)
	{
	
	
		$this->writeToFile($data);
	}
	
	public function writeToFile($data)
	{
		if($this->isOpenForWriting)
		{
			fwrite($this->batchfile, $data);
		}else{
			trigger_error("File is closed!");
		}
	}

	public function closeFile()
	{
		if(!$this->isOpenForWriting)
		{
			trigger_error("Can not close unwritten file!");
			return;
		}

		$this->writeToFile("\nexit\nexit\nexit\n");
			
		$this->isOpenForWriting = false;
		fclose($this->batchfile); // flush file
	}

	public function getFilePath()
	{
		return $this->path;
	}
}