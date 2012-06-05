<?php

namespace solideagle\plugins\smartschool;

use solideagle\logging\Logger;

use solideagle\utilities\XMLParser;

use solideagle\plugins\smartschool\data_access\Api;

class GroupsAndUsersCache
{
	private static $isInitialized = false;
	private static $userCache = array();

	/**
	 * 
	 * @param String $username
	 * @return array
	 */
	
	public static function getUserGroupCodes($username)
	{
		if(!self::$isInitialized)
		{
			Logger::log("Building groups and users cache for smartschool",PEAR_LOG_INFO);
			self::buildCache();
			Logger::log("Cache for smartschool built",PEAR_LOG_INFO);
			self::$isInitialized = true;
		}
		
		if(isset(self::$userCache[$username]))
		{
			return self::$userCache[$username]->groups;
		}
		
		return array();
	}

	private static function buildCache()
	{
		$groups = array();

		$api = Api::singleton();

		$parser = new XMLParser();

		$parser->parse(base64_decode($api->getAllGroupsAndClasses()),function($elem,$data) use (&$groups){
			if($elem == "CODE" && $data !="")
			{
				$groups[$data] = $data; //only unique groeps
			}
		});

		$userCache = array();

		foreach($groups as $groupcode)
		{
			
			$parser->parse(base64_decode($api->getAllAccounts($groupcode,0)),function($elem,$data) use (&$userCache,&$groupcode){
				if($elem == "GEBRUIKERSNAAM")
				{
					if(!isset($userCache[$data]))
					{
						$userCache[$data] = new cachedUser($data);	
					}
					$cacheduser = $userCache[$data];
					$cacheduser->groups[] = $groupcode;
				}
			});
		}

		self::$userCache = $userCache;
		//var_dump(self::$userCache);
	}
}

class cachedUser
{

	public function __construct($username)
	{
		$this->username = $username;
	}
	public $username;
	public $groups = array();
}


