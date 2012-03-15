<?php
namespace ad;

require_once 'scripts/scriptInterface.php';

class oumanager implements \scriptInterface
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
				$grouptaskqueue->addErrorMessage("Probleem met configuratie");
				return false;
			}
				
			
			
			if($params["action"] == "Add")
			{
				return false; //it failed for some reason
			}else{
				return false; //it failed for some reason
			}
			
			//create on AD
			
			$grouptaskqueue->setRollback_Configuration(array("action" => "Remove"));
			
			
			
		
			return true; //succes
	}
	
	
	
}

?>