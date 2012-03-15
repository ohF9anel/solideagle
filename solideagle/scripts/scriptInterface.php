<?php


interface scriptInterface
{
	
	public function getParams();
	
	/**
	 * 
	 * @param GroupTaskQueue $grouptaskqueue
	 * 
	 * return true when succes, false when fail
	 */
	public function runScript($grouptaskqueue);
	
	
}


?>