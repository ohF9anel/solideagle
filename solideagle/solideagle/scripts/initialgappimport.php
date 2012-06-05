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
		$commands ="info user adm_bb@dbz.be
info user adm_ms@dbz.be
info user adm_nc@dbz.be
info user admcanon@dbz.be
info user admgb@dbz.be
info user Admin_SE@dbz.be
info user admjd@dbz.be
info user admmds@dbz.be
info user admps@dbz.be
info user baeyensm10@dbz.be
info user beausaertn@dbz.be
info user boeykensa11@dbz.be
info user boeykensn11@dbz.be
info user braekmanl@dbz.be
info user declercqa@dbz.be
info user declercqt1@dbz.be
info user decockj@dbz.be
info user deraedtj@dbz.be
info user derammelaerej09@dbz.be
info user deseurec@dbz.be
info user desmetj@dbz.be
info user duchis@dbz.be
info user gast01@dbz.be
info user gioto@dbz.be
info user icttest@dbz.be
info user kintg@dbz.be
info user leerkrachtt@dbz.be
info user leerkrachtt1@dbz.be
info user leerkrachtt2@dbz.be
info user leerkrachtt3@dbz.be
info user leerlingt121@dbz.be
info user leerlingt122@dbz.be
info user lotermanj@dbz.be
info user maesb@dbz.be
info user maetl@dbz.be
info user matthijsa@dbz.be
info user ouder11@dbz.be
info user ozlemy@dbz.be
info user roelsh@dbz.be
info user roosense@dbz.be
info user smessaertt@dbz.be
info user stafft@dbz.be
info user testlk1@dbz.be
info user teststaff@dbz.be
info user teststaff2@dbz.be
info user teststaff3@dbz.be
info user teststaff4@dbz.be
info user teststaff5@dbz.be
info user teststaff6@dbz.be
info user thirya@dbz.be
info user vanaelstg@dbz.be
info user vandepittet08@dbz.be
info user vandeveldes11@dbz.be
info user vanwonterghemc@dbz.be
info user verbeurgta08@dbz.be
info user vernaeved@dbz.be
info user verniersm11@dbz.be
info user vervincktl07@dbz.be";

		$arrcmds = explode("\n", $commands);

		foreach($arrcmds as $cmd)
		{

			$username = strstr($cmd, "info user ");
			$username = substr($username,10);
			$username = strstr($username,"@",true);
				
			$myperson = Person::getPersonByUsername($username);
				
			if($myperson === NULL)
			{
				echo $username . "not found\n";
				continue;

			}else{
				$cfg = PlatformGA::getPlatformConfigByPersonId($myperson->getId());
				if($cfg !== NULL)
				{
					echo $username . "already has GA\n";
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
