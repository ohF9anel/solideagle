<?php

namespace solideagle\scripts;

use solideagle\data_access\Group;

use solideagle\data_access\PlatformAD;

use solideagle\data_access\platforms;

use solideagle\data_access\Person;

use solideagle\data_access\Type;

class InitialAdImport
{

	public static function doImport()
	{
		$conn = ldap_connect('ldap://atlas5');
		$anon = ldap_bind($conn);
		if (!$anon) {
			echo "Failed to connect to AD";
			ldap_close($conn);
			return;
		}

		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

		if (!ldap_bind($conn, "icttest@dbz.lok", "ChaCha69"))
		{
			echo "Login failed";
			ldap_close($conn);
			return;
		}
		
		
		$sr = ldap_search($conn, "OU=leerkrachten,OU=gebruikers,DC=dbz,DC=lok", "(&(objectCategory=person)(objectClass=user))");
		$usrs = ldap_get_entries($conn, $sr);
		
		echo "<pre>";
		
		foreach($usrs as $ldapusr)
		{
			set_time_limit(5);
			
			$tmpstdusr = new \stdClass();
			
			$tmpstdusr->uniqueId = $ldapusr["employeeid"][0];
			$tmpstdusr->homedir = $ldapusr["homedirectory"][0];
			$tmpstdusr->accountname = $ldapusr["samaccountname"][0];
			$tmpstdusr->lastname = $ldapusr["sn"][0];
			$tmpstdusr->firstname = $ldapusr["givenname"][0];
			
			$rawdn = $ldapusr["distinguishedname"][0];
			
			$rawdn = strstr($rawdn, ",OU=");
			$rawdn = substr($rawdn, 4);
			
			$rawdn = strstr($rawdn, ",",true);
			
			$tmpstdusr->group =$rawdn;
			
			if(!isset($tmpstdusr->lastname))
				continue;
			
			$person = new Person();
			$person->setName($tmpstdusr->lastname);
			$person->setFirstName($tmpstdusr->firstname);
			$person->setAccountUsername($tmpstdusr->accountname);
			$person->setAccountPassword("No Password Known");
			$person->setUniqueIdentifier($tmpstdusr->uniqueId);
			
			if(($group = Group::getGroupByName($tmpstdusr->group)) !== NULL)
			{
				$person->setGroupId($group->getId());
			}else{
				$person->setGroupId(1);
			}
                        
                        // type?
                        $person->addType(new Type(2)); // 2 = leerkracht, 3 = leerling
			
			$personid = Person::addPerson($person);
			$person->setId($personid);
			
			$platformAD = new PlatformAD();
			$platformAD->setPersonId($person->getId());
			$platformAD->setEnabled(1);
			$platformAD->setHomedir($tmpstdusr->homedir);
			
			PlatformAD::addToPlatform($platformAD);
			
			
		}
		

		
		echo "</pre>";
		
		ldap_close($conn);
		
		
	}
}
