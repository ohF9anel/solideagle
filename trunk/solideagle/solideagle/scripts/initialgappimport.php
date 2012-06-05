<?php

namespace solideagle\scripts;

use solideagle\data_access\PlatformGA;

use solideagle\data_access\Person;

use solideagle\plugins\ga\GamExecutor;

class InitialGappImport
{

	private static $userArr = array();

	public static function doImport()
	{
		self::doRecurse(self::getGroupMembers("dbzgebruikers"));

		var_export(self::$userArr);
	}

	private static function doRecurse($members)
	{
		var_dump($members);

		foreach($members as $member)
		{
			if($member->type == "Group")
			{
				self::doRecurse(self::getGroupMembers($member->mail));
			}else{
				self::$userArr[] = $member;
			}
		}
	}

	private static function getGroupMembers($name)
	{
		$cmd = "info group " . $name;

		$res = GamExecutor::executeGamCommandGetResult($cmd);

		var_dump($res);

		$arr = explode("\n", $res);

		$retarr = array();

		foreach($arr as $line)
		{
			$parts = explode(":",$line);
			if(count($parts)>3)
			{
				$retarr[] = new gappObj(substr($parts[1],1,-6), substr($parts[2],1,-15));
			}

		}

		return $retarr;

	}

	public static function doTheDataImport()
	{
		$commands ="info user collen@dbz.be
		info user delportek@dbz.be
		info user delvauxn@dbz.be
		info user denblauwens@dbz.be
		info user galleg@dbz.be
		info user langendriesd@dbz.be
		info user lotermanj@dbz.be
		info user maesb@dbz.be
		info user morell@dbz.be
		info user rosseelf@dbz.be
		info user vanaelstg@dbz.be
		info user vanhuffels@dbz.be
		info user vermeirene@dbz.be";

		$arrcmds = explode("\n", $commands);

		foreach($arrcmds as $cmd)
		{

			$username = strstr($cmd, "info user ");
			$username = substr($username,10);
			$username = strstr($username,"@",true);
				
			$myperson = Person::getPersonByUsername($username);
				
			if($myperson === NULL)
			{
				continue;

			}else{
				$cfg = PlatformGA::getPlatformConfigByPersonId($myperson->getId());
				if($cfg !== NULL)
				{
					continue;
				}
			}
				
			$alias="";

			$res = GamExecutor::executeGamCommandGetResult($cmd);

			$arr = explode("\n", $res);

			$usenextline=false;
			foreach($arr as $line)
			{
				if($usenextline)
				{
					$alias = $line;
					break;
				}
				if(strstr($line,"Nicknames") !== false)
				{
					$usenextline = true;
				}
			}

			$cfg = new PlatformGA();
			$cfg->setPersonId($myperson->getId());
			$cfg->setEnabled(true);
			$cfg->setAliasmail($alias);
			PlatformGA::addToPlatform($cfg);

		}


	}

}

class gappObj
{
	public $mail;
	public $type;

	public static function __set_state($arr)
	{
		echo "info user " . $arr["mail"] . "\n";

	}

	public function __construct($mail,$type)
	{
		$this->mail = $mail;
		$this->type = $type;
	}
}
