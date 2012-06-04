<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\ga\GamExecutor;
use solideagle\plugins\StatusReport;
use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\data_access\helpers\UnicodeHelper;
use solideagle\Config;

class manageuser
{
	public static function addUser($person, $group,$groupparents,$isStudent)
	{
		//add user
		$gamcmd = "create user " . $person->getAccountUsername() . " firstname \"" . $person->getFirstName() .
		"\" lastname \"" . $person->getName();
		
		if($isStudent)
		{
			$gamcmd.= " - " . $group->getName();
		}
		
		
		$gamcmd.= "\" password \"" . $person->getAccountPassword() . "\"";
			
		$report = GamExecutor::executeGamCommand($gamcmd);

		if(!$report->isSucces())
		{
			return $report;
		}

		//add to group
		$gamcmd = "update group \"" . Group::getMailAdd($group) . "\" add member " . $person->getAccountUsername();

		$report = GamExecutor::executeGamCommand($gamcmd);

		if(!$report->isSucces())
		{
			return $report;
		}

		//add to ou
		$gamcmd = "update org \"";

		if ($groupparents != null)
		{
			for($i = sizeof($groupparents) - 1; $i >= 0; $i--)
			{
				$gamcmd .= $groupparents[$i]->getName() . "/";
			}
		}

		$gamcmd .= $group->getName();
		$gamcmd .= "\" add " . $person->getAccountUsername();

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
		$gamcmd = "user \"". $person->getAccountUsername() . "\"sendas \"" . 
		$alias . "\" \"" .  $person->getFirstName() . " " . $person->getName() . "\" default";
		
		$report = GamExecutor::executeGamCommand($gamcmd);
		
		if(!$report->isSucces())
		{
			return $report;
		}
		
		//create signature
		$signature = $person->getFirstName() . " " . $person->getName() . "<br>";
		$signature .= 	$alias . "<br>";
		$signature .= "Don Boscocollege Zwijnaarde<br>";
		$signature .= "Grotesteenweg-Noord 113<br>";
		$signature .= "9052 Zwijnaarde<br>";
		$signature .= "http://www.dbz.be/";
		
		$gamcmd = "user " . $person->getAccountUsername() . " signature \"" . $signature . "\"";
		
		$report = GamExecutor::executeGamCommand($gamcmd);



		return $report;

			
	}



	public static function removeUserFromGroup($groupname, $username)
	{
		$email = $username . "@" . Config::singleton()->googledomain;
		$gamcmd = "update group \"" . UnicodeHelper::cleanEmailString($groupname) . "\" remove " . $email;

		$report = GamExecutor::executeGamCommand($gamcmd);

		return $report;
	}



	public static function updateUser($person, $enabled)
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
	}

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

	public static function setEmailSignature($username, $signature)
	{
		$gamcmd = "user " . $username . " signature \"" . $signature . "\"";

		$report = GamExecutor::executeGamCommand($gamcmd);

		return $report;
	}



}

?>
