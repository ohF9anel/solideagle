<?php
namespace solideagle\scripts;
//general OU manager for all platforms





use solideagle\scripts\smartschool\groupmanager;

use solideagle\data_access\Group;


class groupmanager {

	public static function Add($parents,$newgroup)
	{
		ad\oumanager::prepareAddOu($parents, $newgroup);
		ad\groupmanager::prepareAddGroup($parents,$newgroup);
		ga\oumanager::prepareAddOu($parents,$newgroup);
		smartschool\groupmanager::prepareAddGroup($parents,$newgroup);
	}

	public static function Modify($parents,$oldgroup,$newgroup)
	{
		ad\oumanager::prepareModifyOu($parents,$oldgroup,$newgroup);
		ad\groupmanager::prepareRenameGroup($oldgroup, $newgroup);
		ga\oumanager::prepareUpdateOu($parents,$oldgroup, $newgroup);
		Smartschool\groupmanager::prepareModifyGroup($parents,$oldgroup, $newgroup);
	}

	public static function Delete($parents,$group)
	{
		ad\oumanager::prepareDeleteOu($parents, $group);
		ad\groupmanager::prepareRemoveGroup($group);
		ga\oumanager::prepareRemoveOu($parents,$group);
		Smartschool\prepareRemoveGroup($parents,$group);
	}

	//wtf?
	public static function Move($oldparents, $newparents, $group, $oldchildren, $newchildren)
	{
		ad\oumanager::prepareMoveOu($oldparents,$newparents,$group);
		//wtf?
		ad\groupmanager::prepareMoveGroup($group, $newparents[0], $newchildren, $oldparents[0], $oldchildren);
		ga\oumanager::prepareMoveOu($newparents,$group, $oldparents);
		martschool\prepareMoveGroup($oldparents,$newparents,$group);
	}


}


?>