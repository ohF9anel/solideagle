<?php

namespace solideagle\plugins;


class StatusReport
{
	
	private $succes;
	private $error;
	
	public function __construct($succes=true,$error="")
	{
		$this->succes = $succes;
		$this->error = $error;
	}
	
	public function isSucces()
	{
		return $this->succes;
	}
	
	public function getError()
	{
		return $this->error;
	}
	
}

?>