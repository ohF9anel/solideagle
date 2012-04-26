<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class PlatformSS
{
	private $enabled = true;
	private $personId;
	
	/**
	 * 
	 * @param PlatformAD $platformAD
	 */
	public static function addToPlatform($platform)
	{
		$sql = "INSERT INTO `platform_ss`
				(`person_id`,
				`enabled`)
				VALUES
				(
				:personid,
				:enabled
				)";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":personid", $platform->getPersonId());
		$cmd->addParam(":enabled", $platform->getEnabled());

		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}
	
	
	public static function getPlatformConfigByPersonId($personid)
	{
		$sql = "SELECT
					`platform_ss`.`person_id`,
					`platform_ss`.`enabled`
					FROM `platform_ss`
					WHERE `platform_ss`.`person_id` = :personid";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":personid",$personid);
		
		if(($obj = $cmd->executeReader()->read()) === false)
		{
			return NULL;
		}				

		$ret = new PlatformSS();
		
		$ret->setEnabled($obj->enabled);
		$ret->setPersonId($obj->person_id);
	
		
		return $ret;
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