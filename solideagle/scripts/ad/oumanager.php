<?php


require_once 'scripts/scriptInterface.php';

class oumanager implements scriptInterface
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
		
		$params = $grouptaskqueue->getConfiguration();
		
			echo"<pre>";
			var_dump($grouptaskqueue);
			
			var_dump($params);
			echo"</pre>";
			
		
			if(!isset($params["action"]))
			{
				$grouptaskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}
				
			
			
			if($params["action"] == "Add")
			{
				$grouptaskqueue->setErrorMessages("Not yet implemented");
				return false; //it failed for some reason
			}else{
				$grouptaskqueue->setErrorMessages("Probleem met configuratie");
				return false; //it failed for some reason
			}
			
			//create on AD
			
			$grouptaskqueue->setRollback_Configuration(array("action" => "Remove"));
			
			
			
		
			return true; //succes
	}
	
	
	
}

?>