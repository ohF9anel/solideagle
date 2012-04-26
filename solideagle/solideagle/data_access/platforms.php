<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;
use solideagle\logging\Logger;


//WTF!!!!!!!
//zie: platformad.php voor correcte implementatie
class platforms
{
	const PLATFORM_AD = "active directory";
	const PLATFORM_SMARTSCHOOL = "smartschool";
	const PLATFORM_GAPP = "google apps";

	/*
	// variables
	private $platformType;
	private $personId;
	private $enabled;

	// getters, setters & functions

	public function getPlatformType()
	{
		return $this->platformType;
	}

	public function setPlatformType($platformType)
	{
		$this->platformType = $platformType;
	}

	public function getPersonId()
	{
		return $this->personId;
	}

	public function setPersonId($personId)
	{
		$this->personId = $personId;
	}

	public function getEnabled()
	{
		return $this->enabled;
	}

	public function setEnabled($enabled)
	{
		$this->enabled = $enabled;
	}

	// manage persons in platform_ad

	public static function addPlatform($platform)
	{
		$sql = "INSERT INTO `" . self::getPlatformTable($platform->getPlatformType()) . "`
		(
		`person_id`,
		`enabled`
		)
		VALUES
		(
		:person_id,
		:enabled
		);";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platform->getPersonId());
		$cmd->addParam(":enabled", $platform->getEnabled());

		$cmd->BeginTransaction();

		$cmd->execute();

		$cmd->CommitTransaction();
	}

	public static function updatePlatform($platform)
	{
		$sql = "UPDATE `" . self::getPlatformTable($platform->getPlatformType()) . "`
		SET
		`enabled` = :enabled
		WHERE `person_id` = :person_id
		;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platform->getPersonId());
		$cmd->addParam(":enabled", $platform->getEnabled());

		$cmd->BeginTransaction();

		$cmd->execute();

		$cmd->CommitTransaction();
	}

	public static function removePlatform($platform)
	{
		$sql = "DELETE FROM `" . self::getPlatformTable($platform->getPlatformType()) . "`
		WHERE `person_id` = :person_id;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $platform->getPersonId());

		$cmd->execute();
	}

	public static function getPlatformAdByPersonId($personId)
	{
		$sql = "SELECT * FROM `" . self::getPlatformTable(self::PLATFORM_AD) . "`
		WHERE `person_id` = :person_id;";
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $personId);
		$reader = $cmd->executeReader();
		$retObj = $reader->read();
		if ($retObj != null)
		{
			$platform = new platforms();
			$platform->setPlatformType(self::PLATFORM_AD);
			$platform->setPersonId($retObj->person_id);
			$platform->setEnabled($retObj->enabled);
			return $platform;
		}

		return null;
	}
	 
	public static function getPlatformGappByPersonId($personId)
	{
		$sql = "SELECT * FROM `" . self::getPlatformTable(self::PLATFORM_GAPP) . "`
		WHERE `person_id` = :person_id;";
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $personId);
		$reader = $cmd->executeReader();
		$retObj = $reader->read();
		if ($retObj != null)
		{
			$platform = new platforms();
			$platform->setPlatformType(self::PLATFORM_GAPP);
			$platform->setPersonId($retObj->person_id);
			$platform->setEnabled($retObj->enabled);
			return $platform;
		}

		return null;
	}

	public static function getPlatformSmartschoolByPersonId($personId)
	{
		$sql = "SELECT * FROM `" . self::getPlatformTable(self::PLATFORM_SMARTSCHOOL) . "`
		WHERE `person_id` = :person_id;";
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":person_id", $personId);
		$reader = $cmd->executeReader();
		$retObj = $reader->read();
		if ($retObj != null)
		{
			$platform = new platforms();
			$platform->setPlatformType(self::PLATFORM_SMARTSCHOOL);
			$platform->setPersonId($retObj->person_id);
			$platform->setEnabled($retObj->enabled);
			return $platform;
		}

		return null;
	}

	// helpers

	public static function getPlatformTable($platformType)
	{
		if ($platformType == self::PLATFORM_AD)
			return "platform_ad";
		if ($platformType == self::PLATFORM_GAPP)
			return "platform_ga";
		if ($platformType == self::PLATFORM_SMARTSCHOOL)
			return "platform_ss";

		return null;
	}
*/
}

?>