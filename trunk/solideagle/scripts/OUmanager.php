<?php
namespace scripts;

//general OU manager for all platforms



require_once 'data_access/Group.php';
require_once 'scripts/ad/oumanager.php';

use DataAccess\Group;


class OUmanager {
	
	public static function AddGroup($parents,$newgroup)
	{
		\scripts\ad\oumanager::prepareAddGroup($parents, $newgroup);
		
	}
	
	public static function ModifyGroup($parents,$oldgroup,$newgroup)
	{
		\scripts\ad\oumanager::prepareModifyGroup($parents,$oldgroup,$newgroup);
		
	}
	
	public static function DeleteGroup($parents,$groep)
	{
		\scripts\ad\oumanager::prepareDeleteGroup($parents, $groep);
	}
}


?>