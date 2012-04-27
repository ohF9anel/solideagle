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
	public static function moveUser($person,$newgid,$oldgid)
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
	
	public static function deleteUser($person)
	{
		Person::delPersonById($person->getId());
	
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
			\solideagle\scripts\smartschool\usermanager::prepareRemoveUser($person);
		}
	}
	
	public static function createAccounts($person,$configstdclass)
	{
		if(!$person->getHasAdAccount() && $configstdclass->createAdAccount)
		{
			\solideagle\scripts\ad\usermanager::prepareAddUser($person);
		}
		
		if(!$person->getHasGaccount() && $configstdclass->createGappAccount)
		{
		
			\solideagle\scripts\ga\usermanager::prepareAddUser($person);
		}
		
		if(!$person->getHasSSAccount() && $configstdclass->createSsAccount)
		{
			\solideagle\scripts\smartschool\usermanager::prepareAddUser($person);
		}
	}
	
	public static function deleteAccounts($person,$configstdclass)
	{
		if($person->getHasAdAccount() && $configstdclass->deleteAdAccount)
		{
			\solideagle\scripts\ad\usermanager::prepareDelUser($person);
		}
	
		if($person->getHasGaccount() && $configstdclass->deleteGappAccount)
		{
	
			\solideagle\scripts\ga\usermanager::prepareDelUser($person);
		}
	
		if($person->getHasSSAccount() && $configstdclass->deleteSsAccount)
		{
			\solideagle\scripts\smartschool\usermanager::prepareRemoveUser($person);
		}
	}
}