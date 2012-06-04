<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class PlatformGA
{
	private $enabled = true;
	private $personId;
	private $aliasmail;

	public static function addToPlatform($platformGA)
	{
		$sql = "INSERT INTO `platform_ga`
		(`person_id`,
		`enabled`,
		`aliasmail`)
		VALUES
		(
		:person_id,
		:enabled,
		:aliasmail
		)";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformGA->getPersonId());
		$cmd->addParam(":enabled", $platformGA->getEnabled());
		$cmd->addParam(":aliasmail", $platformGA->getAliasmail());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}

	/**
	 * 
	 * @param PlatformGA $platformGA
	 */
	public static function updatePlatform($platformGA)
	{
		$sql = "UPDATE `platform_ga`
		SET `enabled` = :enabled,
		`aliasmail` = :aliasmail
		WHERE `person_id` = :person_id
		";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platformGA->getPersonId());
		$cmd->addParam(":enabled", $platformGA->getEnabled());
		$cmd->addParam(":aliasmail", $platformGA->getAliasmail());
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}

	public static function getPlatformConfigByPersonId($personid)
	{
		$sql = "SELECT
		`platform_ga`.`person_id`,
		`platform_ga`.`enabled`,
		`platform_ga`.`aliasmail`
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
		$ret->setAliasmail($obj->aliasmail);

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

	public function getJson()
	{
		return json_encode(get_object_vars($this));
	}

	public function getAliasmail()
	{
		return $this->aliasmail;
	}

	public function setAliasmail($aliasmail)
	{
		$this->aliasmail = $aliasmail;
	}
}