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
		$sql = "INSERT INTO `platform_ad`
				(`person_id`,
				`enabled`,
				`homefolder_path`)
				VALUES
				(
				:person_id,
				:enabled,
				:homefolder_path
				)";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformAD->getPersonId());
		$cmd->addParam(":enabled", $platformAD->getEnabled());
		$cmd->addParam(":homefolder_path", $platformAD->getHomedir());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}
        
        public static function updatePlatform($platformAD)
	{
		$sql = "UPDATE `platform_ad`
                        SET `enabled` = :enabled,
                            `homefolder_path` = :homefolder_path
			WHERE `person_id` = :person_id
                        ";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformAD->getPersonId());
		$cmd->addParam(":enabled", $platformAD->getEnabled());
		$cmd->addParam(":homefolder_path", $platformAD->getHomedir());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}
	
	public static function getPlatformConfigByPersonId($personid)
	{
		$sql = "SELECT
					`platform_ad`.`person_id`,
					`platform_ad`.`enabled`,
					`platform_ad`.`homefolder_path`
					FROM `platform_ad`
					WHERE `platform_ad`.`person_id` = :personid";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":personid",$personid);
		
		if(($obj = $cmd->executeReader()->read()) === false)
		{
			return NULL;
		}				

		$ret = new PlatformAD();
		
		$ret->setEnabled($obj->enabled);
		$ret->setPersonId($obj->person_id);
		$ret->setHomedir($obj->homefolder_path);
		
		return $ret;
	}
        
        public static function removePlatformByPersonId($personid)
	{
		$sql = "DELETE FROM `platform_ad`
                        WHERE `platform_ad`.`person_id` = :personid";

		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":personid",$personid);
		
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
	
	
	
	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}
	
}