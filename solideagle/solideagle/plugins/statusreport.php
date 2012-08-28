<?php
/**
 * class to report error and succes messages from tasks
 * 
 * @author Machiel Sleeuwaert <Machiel.Sleeuwaert@dbz.be>
 * $Id$ 
 * Last changed: $LastChangedDate$
 * @author $Author$
 * @version $Revision$
 * 
 * example: <pre><code>return new StatusReport(false, "Connection to AD cannot be made.");</code></pre>
 * 
 */
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