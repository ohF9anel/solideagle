<?php

namespace solideagle\scripts;

use solideagle\data_access\Group;

use solideagle\data_access\Person;

class GlobalUserManager
{
	/**
	 * 
	 * @param Person $person
	 * @param string $newgid
	 */
	public static function moveUser($person, $newgid,$oldgid)
	{

		$person->setGroupId($newgid);
		Person::moveUser($person);
		
		$newgroup = Group::getGroupById($newgid);
		$oldgroup = Group::getGroupById($oldgid);
	
		if($person->getHasAdAccount())
		{
			//TODO
			//\solideagle\scripts\ad\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	
		if($person->getHasGaccount())
		{
			//TODO
			//\solideagle\scripts\ga\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	
		if($person->getHasSSAccount())
		{
			\solideagle\scripts\smartschool\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	}
	
	public static function updateUser($person)
	{
		Person::updatePerson($person);
		
		if($person->getHasAdAccount())
		{
			//TODO
			//\solideagle\scripts\ad\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	
		if($person->getHasGaccount())
		{
			//TODO
			//\solideagle\scripts\ga\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	
		if($person->getHasSSAccount())
		{
			\solideagle\scripts\smartschool\usermanager::prepareUpdateUser($person);
		}
	}
}