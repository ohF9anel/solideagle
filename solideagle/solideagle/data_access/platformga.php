<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class PlatformGA
{
	private $enabled = true;
	private $personId;
	
	public static function addToPlatform($platformGA)
	{
		$sql = "INSERT INTO `platform_ga`
				(`person_id`,
				`enabled`)
				VALUES
				(
				:person_id,
				:enabled
				)";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformGA->getPersonId());
		$cmd->addParam(":enabled", $platformGA->getEnabled());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}
        
        public static function updatePlatform($platformGA)
	{
		$sql = "UPDATE `platform_ga`
                        SET `enabled` = :enabled
			WHERE `person_id` = :person_id
                        ";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformGA->getPersonId());
		$cmd->addParam(":enabled", $platformGA->getEnabled());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}
	
	public static function getPlatformConfigByPersonId($personid)
	{
		$sql = "SELECT
                        `platform_ga`.`person_id`,
                        `platform_ga`.`enabled`
                        FROM `platform_ga`
                        WHERE `platform_ga`.`person_id` = :personid";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":personid",$personid);
		
		if(($obj = $cmd->executeReader()->read()) === false)
		{
			return NULL;
		}				

		$ret = new PlatformGA();
		
		$ret->setEnabled($obj->enabled);
		$ret->setPersonId($obj->person_id);
		
		return $ret;
	}
        
        public static function removePlatformByPersonId($personid)
	{
		$sql = "DELETE FROM `platform_ga`
                        WHERE `platform_ga`.`person_id` = :personid";

		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":personid",$personid);
		
		$cmd->execute();
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