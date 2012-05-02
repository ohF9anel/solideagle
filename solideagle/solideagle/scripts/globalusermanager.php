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
		
		$accountenabled = true; //TODO, moving makes disabled accounts enabled...

		if($person->getHasAdAccount())
		{
			\solideagle\scripts\ad\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}

		if($person->getHasGaAccount())
		{
			\solideagle\scripts\ga\usermanager::prepareMoveUser($person,$newgroup,$oldgroup);
		}

		if($person->getHasSSAccount())
		{
			\solideagle\scripts\smartschool\usermanager::prepareMoveUser($person,$newgroup,$oldgroup,$accountenabled);
		}
	}

	public static function updateUser($person)
	{
		Person::updatePerson($person);
		
		$enabled = true; //TODO: fix

		if($person->getHasAdAccount())
		{
			\solideagle\scripts\ad\usermanager::prepareUpdateUser($person,$enabled);
		}
                
		if($person->getHasGaAccount())
		{
			\solideagle\scripts\ga\usermanager::prepareUpdateUser($person,$enabled);
                        if ($person->getPictureUrl() != null)
                            \solideagle\scripts\ga\usermanager::prepareSetPhoto($person);
		}

		if($person->getHasSSAccount())
		{
			\solideagle\scripts\smartschool\usermanager::prepareUpdateUser($person,$enabled);
		}
	}

	public static function deleteUser($person)
	{
		Person::delPersonById($person->getId());

		if($person->getHasAdAccount())
		{
			\solideagle\scripts\ad\usermanager::prepareDelUser($person);
		}

		if($person->getHasGaAccount())
		{
			\solideagle\scripts\ga\usermanager::prepareDelUser($person);
		}

		if($person->getHasSSAccount())
		{
			\solideagle\scripts\smartschool\usermanager::prepareRemoveUser($person);
		}
	}
	
	//
	// Platforms only
	//

	public static function createAccounts($person,$configstdclass)
	{
		if(!$person->getHasAdAccount() && $configstdclass->createAdAccount)
		{
			\solideagle\scripts\ad\usermanager::prepareAddUser($person);
		}

		if(!$person->getHasGaAccount() && $configstdclass->createGappAccount)
		{
			\solideagle\scripts\ga\usermanager::prepareAddUser($person);
                        if ($person->getPictureUrl() != null)
                            \solideagle\scripts\ga\usermanager::prepareSetPhoto($person);
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

		if($person->getHasGaAccount() && $configstdclass->deleteGappAccount)
		{

			\solideagle\scripts\ga\usermanager::prepareDelUser($person);
		}

		if($person->getHasSSAccount() && $configstdclass->deleteSsAccount)
		{
			\solideagle\scripts\smartschool\usermanager::prepareRemoveUser($person);
		}
	}
	
	public static function enableDisableAccounts($person,$configstdclass)
	{
		if($person->getHasAdAccount())
		{
			if($configstdclass->enableAdAccount)
			{
				\solideagle\scripts\ad\usermanager::prepareUpdateUser($person,true);
			}
			else if($configstdclass->disableAdAccount)
			{
				\solideagle\scripts\ad\usermanager::prepareUpdateUser($person,false);
			}
			
		}

		if($person->getHasGaAccount())
		{
			if($configstdclass->enableGappAccount)
			{
				\solideagle\scripts\ga\usermanager::prepareUpdateUser($person,true);
			}
			else if($configstdclass->disableGappAccount)
			{
				\solideagle\scripts\ga\usermanager::prepareUpdateUser($person,false);
			}
			
		}

		if($person->getHasSSAccount())
		{
			if($configstdclass->enableSsAccount)
			{
				\solideagle\scripts\smartschool\usermanager::prepareUpdateUser($person,true);
			}
			else if($configstdclass->disableSsAccount){
				\solideagle\scripts\smartschool\usermanager::prepareUpdateUser($person,false);
			}
		}
	}
	
	
}