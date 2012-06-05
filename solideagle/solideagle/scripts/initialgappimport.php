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
		$commands ="info user blanchaerti@dbz.be
info user colmann@dbz.be
info user coomanm@dbz.be
info user debeuler@dbz.be
info user deboeverc@dbz.be
info user declercqt@dbz.be
info user decockl@dbz.be
info user deschryvere@dbz.be
info user dewaeled@dbz.be
info user everaertc@dbz.be
info user gallea@dbz.be
info user gallee@dbz.be
info user galleg@dbz.be
info user hertegonneg@dbz.be
info user lippensd@dbz.be
info user pietersg@dbz.be
info user provosth@dbz.be
info user reunesv@dbz.be
info user vandekerckhovem@dbz.be
info user vanpouckea@dbz.be
info user verschraegenk@dbz.be";

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
