<?php

namespace solideagle\utilities;

class StatusReporter
{
	private $progress = 0;
	private $message;
	private $amount;
	private $uniqueid;
	
	public function __construct($msg,$amount,$uniqueid)
	{
		$this->message = $msg;
		$this->amount = $amount;
		$this->uniqueid = $uniqueid;
		
		exec("touch /tmp/". $this->uniqueid . "status.txt");
	}
	
	public function increase()
	{
		$handle = fopen("/tmp/". $this->uniqueid . "status.txt", "w");
		
		fwrite($handle, "STATUSREPORT:" . $this->message . " " . $this->progress . "/" . $this->amount . "\n");
		
		fclose($handle);
		
		$this->progress +=1;
	}
	
	public function endReporter()
	{
		var_dump(exec("rm /tmp/". $this->uniqueid . "status.txt"));
	}
	
	public static function readByUniqueId($uniqueid)
	{
		$handle = fopen("/tmp/". $uniqueid . "status.txt", "r");
		
		if(!$handle)
		{
			header('HTTP/1.1 500 Internal Server Error');
			exit();
		}
		
		return fgets($handle);
		
		fclose($handle);
	}
}


