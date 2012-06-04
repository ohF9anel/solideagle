<?php

namespace solideagle\plugins\ga;

use solideagle\data_access\Type;

use solideagle\data_access\PlatformGA;

use solideagle\plugins\ga\GamExecutor;
use solideagle\plugins\StatusReport;
use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\data_access\helpers\UnicodeHelper;
use solideagle\Config;

class manageuser
{
	public static function addUser($person, $group,$groupparents)
	{
		$isStudent = $person->isTypeOf(Type::TYPE_LEERLING);
		
		$personlastname = self::genLastName($person,$group);

		//add user
		$gamcmd = "create user " . $person->getAccountUsername() . " firstname \"" . $person->getFirstName() .
		"\" lastname \"" . $personlastname . "\" password \"" . $person->getAccountPassword() . "\"";
			
		$report = GamExecutor::executeGamCommand($gamcmd);

		if(!$report->isSucces())
		{
			return $report;
		}

		//create alias
		for($i = 0; $i < 25; $i++)
		{

			$alias = UnicodeHelper::cleanEmailString($person->getFirstName()) .
			"." . UnicodeHelper::cleanEmailString($person->getName());

			if ($i != 0)
				$alias .= $i;

			if($isStudent)
			{
				$alias.= "@" . Config::singleton()->googledomainstudent;
			}else{
				$alias.= "@" . Config::singleton()->googledomain;
			}

			$gamcmd = "create nickname " . $alias  . " user \"" . $person->getAccountUsername()  . "\"";

			$report = GamExecutor::executeGamCommand($gamcmd);

			if(!$report->isSucces() && strpos($report->getError(), "Entity exists") !== false )
				continue;

			break;
		}

		if(!$report->isSucces())
		{
			return $report;
		}

		//set sendas
		$report = self::createSendAs($person->getAccountUsername(),$alias,$person->getFirstName() . " " . $personlastname);

		if(!$report->isSucces())
		{
			return $report;
		}

		//create signature
		$report = self::createSignature($person->getAccountUsername(),$person->getFirstName() . " " .$personlastname,$alias);

		if(!$report->isSucces())
		{
			return $report;
		}

		//add to group
		$report = self::addToGroup($person->getAccountUsername(),Group::getMailAdd($group));

		if(!$report->isSucces())
		{
			return $report;
		}

		//add to ou
		$report = self::addToOu($person->getAccountUsername(),$group,$groupparents);

		if(!$report->isSucces())
		{
			return $report;
		}

		$platform = new PlatformGA();
		$platform->setPersonId($person->getId());
		$platform->setEnabled(true);
		$platform->setAliasmail($alias);
		PlatformGA::addToPlatform($platform);

		return $report;
	}

	public static function moveUser($person,$mailalias,$oldgroupname, $group,$groupparents)
	{
		$personlastname = self::genLastName($person, $group);

		//remove from old group
		$gamcmd = "update group \"" . $oldgroupname . "\" remove " . $person->getAccountUsername() . "@" . Config::singleton()->googledomain;
		$report = GamExecutor::executeGamCommand($gamcmd);

		if(!$report->isSucces())
		{
			return $report;
		}

		//add to new group
		$report = self::addToGroup($person->getAccountUsername(),Group::getMailAdd($group));

		if(!$report->isSucces())
		{
			return $report;
		}

		//add to new ou
		$report = self::addToOu($person->getAccountUsername(),$group,$groupparents);

		if(!$report->isSucces())
		{
			return $report;
		}


		//update name
		$report = self::updateUser($person->getAccountUsername(),$person->getFirstName(),$personlastname);
		
		if(!$report->isSucces())
		{
			return $report;
		}


		//set sendas
		$report = self::createSendAs($person->getAccountUsername(),$mailalias,$person->getFirstName() . " " . $personlastname);

		if(!$report->isSucces())
		{
			return $report;
		}


		return $report;
	}



	/*public static function updateUser($person, $enabled)
	{
		$gamcmd = "update user " . $person->getAccountUsername() . " ";
		$gamcmd .= "firstname \"" . $person->getFirstName() . "\" ";
		$gamcmd .= "lastname \"" . $person->getName() . "\" ";

		if (!$enabled)
			$gamcmd .= "suspended on";
		else
			$gamcmd .= "suspended off";

		$report = GamExecutor::executeGamCommand($gamcmd);
			
		return $report;
	}*/

	public static function updatePassword($username, $password)
	{
		$gamcmd = "update user " . $username . " ";
		$gamcmd .= "password " . $password . " ";

		$report = GamExecutor::executeGamCommand($gamcmd);

		return $report;
	}

	public static function removeUser($person)
	{
		$gamcmd = "delete user " . $person->getAccountUsername();

		$report = GamExecutor::executeGamCommand($gamcmd);

		return $report;
	}

	public function setPhoto($username, $filepath)
	{
		$gamcmd = "user " . $username . " update photo " . $filepath;

		$report = GamExecutor::executeGamCommand($gamcmd);

		return $report;
	}

	private static function addToOu($username,$group,$groupparents)
	{
		$gamcmd = "update org \"";

		if ($groupparents != null)
		{
			for($i = sizeof($groupparents) - 1; $i >= 0; $i--)
			{
				$gamcmd .= $groupparents[$i]->getName() . "/";
			}
		}

		$gamcmd .= $group->getName();
		$gamcmd .= "\" add " . $username;

		return GamExecutor::executeGamCommand($gamcmd);
	}

	private static function createSignature($username,$name,$aliasmail)
	{
		$signature =   $name . "<br>";
		$signature .=  $aliasmail . "<br>";
		$signature .= "Don Boscocollege Zwijnaarde<br>";
		$signature .= "Grotesteenweg-Noord 113<br>";
		$signature .= "9052 Zwijnaarde<br>";
		$signature .= "http://www.dbz.be/";

		$gamcmd = "user " . $username . " signature \"" . $signature . "\"";

		return GamExecutor::executeGamCommand($gamcmd);
	}

	private static function createSendAs($username,$aliasmail,$aliasname)
	{
		$gamcmd = "user \"". $username . "\" sendas \"" .
				$aliasmail . "\" \"" .  $aliasname . "\" default";

		return GamExecutor::executeGamCommand($gamcmd);
	}

	private static function addToGroup($username,$groupname)
	{
		$gamcmd = "update group \"" . $groupname . "\" add member " . $username;

		return GamExecutor::executeGamCommand($gamcmd);
	}
	
	private static function genLastName($person,$group)
	{
		$personlastname = $person->getName();
		
		if($person->isTypeOf(Type::TYPE_LEERLING))
		{
			 $personlastname.= " - " . $group->getName();
		}
		
		return $personlastname;
	}

	private static function updateUser($username,$firstname,$lastname)
	{
		$gamcmd = "update user " . $username . " ";
		$gamcmd .= "firstname \"" . $firstname . "\" ";
		$gamcmd .= "lastname \"" . $lastname . "\" ";
		
		return GamExecutor::executeGamCommand($gamcmd);
	}

}

?>
