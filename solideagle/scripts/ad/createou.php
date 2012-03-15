<?php
namespace ad;

require_once 'scripts/scriptInterface.php';

class createou implements \scriptInterface
{
	public function getParams()
	{
		
	}
	
	/**
	 * (non-PHPdoc)
	 * @see scriptInterface::runScript()
	 */
	public function runScript($grouptaskqueue)
	{
			echo"<pre>";
			var_dump($grouptaskqueue);
			echo"</pre>";
			
			//create on AD
			
			
			
		
			return true;
	}
	
	
	
}

?>