<?php
namespace solideagle\scripts;
//general OU manager for all platforms





use solideagle\data_access\Group;


class groupmanager {
	
	public static function Add($parents,$newgroup)
	{
		ad\oumanager::prepareAddOu($parents, $newgroup);
		ad\groupmanager::prepareAddGroup($newgroup);
                ga\oumanager::prepareAddOu($newgroup);
	}
	
	public static function Modify($parents,$oldgroup,$newgroup)
	{
		ad\oumanager::prepareModifyOu($parents,$oldgroup,$newgroup);
                ad\groupmanager::prepareRenameGroup($oldgroup, $newgroup);
                ga\oumanager::prepareUpdateOu($oldgroup, $newgroup);
	}
	
	public static function Delete($parents,$group)
	{
		ad\oumanager::prepareDeleteOu($parents, $group);
                ad\groupmanager::prepareRemoveGroup($group);
                ga\oumanager::prepareRemoveOu($group);
	}
	
	public static function Move($oldparents, $newparents, $group, $oldchildren, $newchildren)
	{
		ad\oumanager::prepareMoveOu($oldparents,$newparents,$group);
                ad\groupmanager::prepareMoveGroup($group, $newparents[0], $newchildren, $oldparents[0], $oldchildren);
                ga\oumanager::prepareMoveOu($group, $oldparents);
	}
	

}


?>