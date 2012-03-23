<?php
namespace scripts;

//general OU manager for all platforms



require_once 'data_access/Group.php';
require_once 'scripts/ad/groupmanager.php';

use DataAccess\Group;


class OUmanager {
	
	public static function AddGroup($parents,$newgroup)
	{
		\scripts\ad\groupmanager::prepareAddGroup($parents, $newgroup);
		
	}
	
	public static function ModifyGroup($parents,$oldgroup,$newgroup)
	{
		\scripts\ad\groupmanager::prepareModifyGroup($parents,$oldgroup,$newgroup);
		
	}
	
	public static function DeleteGroup($parents,$groep)
	{
		\scripts\ad\groupmanager::prepareDeleteGroup($parents, $groep);
	}
}


?>