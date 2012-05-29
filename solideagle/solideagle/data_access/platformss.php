<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class PlatformSS
{
	private $enabled = true;
	private $personId;
	
	public static function addToPlatform($platformss)
	{
		$sql = "INSERT INTO `platform_ss`
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
		$sql = "UPDATE `platform_ss`
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
        
        public static function removePlatformByPersonId($personid)
	{
		$sql = "DELETE FROM `platform_ss`
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
	
	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}
}