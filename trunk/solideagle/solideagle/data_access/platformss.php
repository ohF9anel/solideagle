<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class PlatformSS
{
	private $enabled = true;
	private $personId;
	
	public static function addToPlatform($platformss)
	{
		$sql = "INSERT INTO `CentralAccountDB`.`platform_ss`
				(`person_id`,
				`enabled`)
				VALUES
				(
				:person_id,
				:enabled
				)";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformss->getPersonId());
		$cmd->addParam(":enabled", $platformss->getEnabled());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}
        
        public static function updatePlatform($platformss)
	{
		$sql = "UPDATE `CentralAccountDB`.`platform_ss`
                        SET `enabled` = :enabled
			WHERE `person_id` = :person_id
                        ";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformss->getPersonId());
		$cmd->addParam(":enabled", $platformss->getEnabled());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}
	
	public static function getPlatformConfigByPersonId($personid)
	{
		$sql = "SELECT
					`platform_ss`.`person_id`,
					`platform_ss`.`enabled`
					FROM `CentralAccountDB`.`platform_ss`
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
        
        public static function removePlatformByPersonId($personid)
	{
		$sql = "DELETE FROM `CentralAccountDB`.`platform_ss`
                        WHERE `platform_ss`.`person_id` = :personid";

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