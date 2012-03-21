<?php



class BaseTaskQueue
{
	private $id;
	private $task_id;
	private $configuration;
	private $rollback_configuration;
	private $path_script;
	private $taskname;
	private $errorcount;
	private $errormessages;

	public function getId()
	{
	    return $this->id;
	}

	public function setId($id)
	{
	    $this->id = $id;
	}

	public function getTask_id()
	{
	    return $this->task_id;
	}

	public function setTask_id($task_id)
	{
	    $this->task_id = $task_id;
	}


	public function getPath_script()
	{
	    return $this->path_script;
	}

	public function setPath_script($path_script)
	{
	    $this->path_script = $path_script;
	}

	public function getTaskname()
	{
	    return $this->taskname;
	}

	public function setTaskname($taskname)
	{
	    $this->taskname = $taskname;
	}

	public function getErrorcount()
	{
	    return $this->errorcount;
	}

	public function setErrorcount($errorcount)
	{
	    $this->errorcount = $errorcount;
	}

	public function getErrormessages()
	{
	    return $this->errormessages;
	}
	
	public function setErrorMessages($errormessages)
	{
		$this->errormessages = $errormessages;
	}

	public function addErrormessage($errormessages)
	{
	    $this->errormessages .=   "\n" . $errormessages;
	}
	
	
	/********************/
	
	//PHP 5.3 does not support $this or ::self in closures
	//not so pretty workaround
	
	public function setConfigurationFromDb($config)
	{
		$this->configuration = $config;
	}
	
	public function getConfigurationForDb()
	{
		return $this->configuration;
	}
	
	public function setRollback_ConfigurationFromDb($config)
	{
		$this->rollback_configuration = $config;
	}
	
	public function getRollback_ConfigurationForDb()
	{
		return $this->rollback_configuration;
	}
	
	/****************/
	
	public function getConfiguration()
	{
		
		$conf = @unserialize(base64_decode($this->configuration));
		
		return $conf;
	}
	
	public function setConfiguration($configuration)
	{
		$this->configuration =  base64_encode(serialize($configuration));
	}
	
	public function getRollback_configuration()
	{
	
		$conf = @unserialize(base64_decode($this->rollback_configuration));

		
		return $conf;
		 
	}
	
	public function setRollback_configuration($rollback_configuration)
	{
		$this->rollback_configuration =  base64_encode(serialize($rollback_configuration));
	}
}

?>