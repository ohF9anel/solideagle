<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class PlatformAD
{
	private $homedir;
	private $enabled = true;
	private $personId;
	
	/**
	 * 
	 * @param PlatformAD $platformAD
	 */
	public static function addToPlatform($platformAD)
	{
		$sql = "INSERT INTO `CentralAccountDB`.`platform_ad`
				(`person_id`,
				`enabled`,
				`homefolder_path`)
				VALUES
				(
				:personid,
				:enabled,
				:homefolder_path
				)";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":personid", $platformAD->getPersonId());
		$cmd->addParam(":enabled", $platformAD->getEnabled());
		$cmd->addParam(":homefolder_path", $platformAD->getHomedir());
		
		$cmd->execute();
	}

	public function getHomedir()
	{
	    return $this->homedir;
	}

	public function setHomedir($homedir)
	{
	    $this->homedir = $homedir;
	}

	public function getEnabled()
	{
	    return $this->enabled;
	}

	public function setEnabled($enabled)
	{
	    $this->enabled = $enabled;
	}

	public function getPersonId()
	{
	    return $this->personId;
	}

	public function setPersonId($personId)
	{
	    $this->personId = $personId;
	}
}