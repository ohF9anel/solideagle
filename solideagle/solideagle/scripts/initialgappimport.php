<?php

namespace solideagle\scripts;

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
		$cmd = "gam info group";
		
		$res = GamExecutor::executeGamCommandGetResult($cmd);
		
		$arr = explode("\n", $res);
		
		$retarr = array();
		
		foreach($arr as $line)
		{
			$parts = explode(":",$line);
			if(count($parts)>2)
			{
				$retarr[] = new gappObj(substr($parts[1],1,-6), substr($parts[2],1,-15));
			}
			
		}
		
		return $retarr[];

	}
	
}

class gappObj
{
	public $mail;
	public $type;
	
	public function __construct($mail,$type)
	{
		$this->mail = $mail;
		$this->type = $type;
	}
}