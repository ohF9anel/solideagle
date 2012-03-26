<?php
namespace solideagle\scripts;
//general OU manager for all platforms





use solideagle\data_access\Group;


class OUmanager {
	
	public static function Add($parents,$newgroup)
	{
		ad\oumanager::prepareAddGroup($parents, $newgroup);
		
	}
	
	public static function Modify($parents,$oldgroup,$newgroup)
	{
		ad\oumanager::prepareModifyGroup($parents,$oldgroup,$newgroup);
		
	}
	
	public static function Delete($parents,$groep)
	{
		ad\oumanager::prepareDeleteGroup($parents, $groep);
	}
	
	public static function Move($oldparents,$newparents,$group)
	{
		ad\oumanager::prepareMoveGroup($oldparents,$newparents,$group);
	}
	
	public static function Move($oldparents,$newparents,$group)
	{
		\scripts\ad\oumanager::prepareMoveGroup($oldparents,$newparents,$group);
	}
}


?>