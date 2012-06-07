<?php

namespace solideagle\scripts;

use solideagle\data_access\database\DatabaseCommand;

class oldDeleter
{

	public static function deleteOldGroups()
	{
		$cmd = new DatabaseCommand();

		$sql = "DELETE FROM `group` 
		WHERE deleted = 1";

		$cmd->newQuery($sql);

		$cmd->execute();
	}

	public static function deleteOldUsers()
	{
		$cmd = new DatabaseCommand();
		$sql = "DELETE FROM  `person`
		WHERE deleted = 1;";

		$cmd->newQuery($sql);

		$cmd->execute();
	}

}





?>