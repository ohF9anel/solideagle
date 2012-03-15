<?php


interface scriptInterface
{
	
	public function getParams();
	
	/**
	 * 
	 * @param GroupTaskQueue $grouptaskqueue
	 */
	public function runScript($grouptaskqueue);
	
	
}


?>