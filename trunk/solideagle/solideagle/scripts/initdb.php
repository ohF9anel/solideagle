<?php
namespace solideagle\scripts;

use solideagle\data_access\Group;

use solideagle\data_access\database\DatabaseCommand;

class initdb
{
	
	public static function startclean()
	{
		$sql = "truncate course";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->execute();
		
		$sql = "truncate type_person";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$sql = "truncate default_type_group";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$sql = "truncate group_closure";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		

		
		$sql = "truncate platform_ad";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$sql = "truncate platform_ga";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$sql = "truncate platform_ss";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$sql = "truncate task_queue";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$sql = "truncate task_rollback";
		
		$cmd->newQuery($sql);
		$cmd->execute();
	
		$sql = "truncate person";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$sql = "truncate `group`";
		
		$cmd->newQuery($sql);
		$cmd->execute();
		
		$root = new Group();
		
		$root->setName("dbzgebruikers");
		
		Group::addGroup($root);
		
	}
	
	
}