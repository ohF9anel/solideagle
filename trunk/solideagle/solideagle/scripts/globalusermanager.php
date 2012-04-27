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
	
		if(platforms::getPlatformAdByPersonId($person->getId()) !== NULL)
		{
			//TODO
			//\solideagle\scripts\ad\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	
		if(platforms::getPlatformGappByPersonId($person->getId()) !== NULL)
		{
			//TODO
			//\solideagle\scripts\ga\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	
		if(platforms::getPlatformSmartschoolByPersonId($person->getId()) !== NULL)
		{
			\solideagle\scripts\smartschool\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}
	}
	
	public static function updateUser($person)
	{
		Person::updatePerson($person);
		
		/*if (platforms::getPlatformAdByPersonId($person->getId()) != null)
		 {
		solideagle\scripts\ad\usermanager::prepareUpdateUser($person);
		}
		if (platforms::getPlatformGappByPersonId($person->getId()) != null)
		{
		solideagle\scripts\ga\usermanager::prepareUpdateUser($person, $oldPerson->getAccountUsername());
		}
		if (platforms::getPlatformSmartschoolByPersonIdByPersonId($person->getId()) != null)
			solideagle\scripts\smartschool\usermanager::prepareUpdateUser($person);*/
	}
}