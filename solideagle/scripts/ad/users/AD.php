<?php

require_once 'scripts/scriptInterface.php';

class AD implements scriptInterface
{
	public function runScript($taskqueue)
	{
		
		echo $taskqueue->getTaskName();
		
		$taskqueue->setErrorMessages("not yet implemented");
		return false;
	}
	
	public function getParams()
	{
		
	}
}

?>