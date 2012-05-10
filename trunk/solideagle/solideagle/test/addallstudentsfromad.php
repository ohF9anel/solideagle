<?php

namespace solideagle\scripts;

use solideagle\data_access\Group;

use solideagle\data_access\PlatformAD;

use solideagle\data_access\platforms;

use solideagle\data_access\Person;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


		$conn = ldap_connect('ldap://atlas5');
		$anon = ldap_bind($conn);
		if (!$anon) {
			echo "Failed to connect to AD";
			ldap_close($conn);
			return;
		}

		ldap_set_option($conn, LDAP_OPT_PROTOCOL_VERSION, 3);
		ldap_set_option($conn, LDAP_OPT_REFERRALS, 0);

		if (!ldap_bind($conn, "icttest@dbz.lok", ""))
		{
			echo "Login failed";
			ldap_close($conn);
			return;
		}
		
		
		$sr = ldap_search($conn, "OU=leerlingen,OU=gebruikers,DC=dbz,DC=lok", "(&(objectCategory=person)(objectClass=user))");
		$usrs = ldap_get_entries($conn, $sr);
		
		foreach($usrs as $ldapusr)
		{
			set_time_limit(5);
			
			$tmpstdusr = new \stdClass();
			
			$tmpstdusr->uniqueId = $ldapusr["employeeid"][0];
			$tmpstdusr->homedir = $ldapusr["homedirectory"][0];
			$tmpstdusr->accountname = $ldapusr["samaccountname"][0];
                        var_dump($tmpstdusr->accountname);
			$p = Person::getPersonByUsername($tmpstdusr->accountname);
                        
                        if ($p != null)
                        {
                            $p->setUniqueIdentifier($tmpstdusr->uniqueId);

                            Person::updatePerson($p);

                            $platformAD = new PlatformAD();
                            $platformAD->setPersonId($p->getId());
                            $platformAD->setEnabled(1);
                            $platformAD->setHomedir($tmpstdusr->homedir);

                            PlatformAD::updatePlatform($platformAD);
                        }
		}

		ldap_close($conn);
		
?>
