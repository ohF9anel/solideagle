<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Person;
use solideagle\data_access\platforms;

use solideagle\plugins\ga\manageuser;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class usermanager implements TaskInterface
{
	const ActionAddUser = 0;
        const ActionUpdateUser = 1;
        const ActionDelUser = 2;
        const ActionAddUserToOu = 3;
        
        public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddUser && isset($config["user"]) && isset($config["enabled"]))
		{  
                    $ret = manageuser::addUser($config["user"], $config["enabled"]);
                        
                    if($ret->isSucces())
                    {
                        $platform = new platforms();
                        $platform->setPlatformType(platforms::PLATFORM_GAPP);
                        $platform->setPersonId($config["user"]->getId());
                        $platform->setEnabled($config["enabled"]);
                        platforms::addPlatform($platform);
                        return true;
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                else if($config["action"] == self::ActionUpdateUser && isset($config["user"]) && isset($config["oldUsername"]) && isset($config["enabled"]))
                {
                    $ret = manageuser::updateUser($config["user"], $config["oldUsername"], $config["enabled"]);
                    
                    if($ret->isSucces())
                    {
                        $platform = new platforms();
                        $platform->setPlatformType(platforms::PLATFORM_GAPP);
                        $platform->setPersonId($config["user"]->getId());
                        $platform->setEnabled($config["enabled"]);
                        platforms::updatePlatform($platform);
                        return true;
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                else if($config["action"] == self::ActionDelUser && isset($config["user"]))
                {
                    $ret = manageuser::removeUser($config["user"]);
  
                    if($ret->isSucces())
                    {
                        $platform = new platforms();
                        $platform->setPlatformType(platforms::PLATFORM_GAPP);
                        $platform->setPersonId($config["person"]->getId());
                        platforms::removePlatform($platform);
                        return true;
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                else if($config["action"] == self::ActionAddUserToOu && isset($config["user"]))
                {
                    $ret = manageuser::addUserToOu($config["user"]);
  
                    if($ret->isSucces())
                    {
                        return true;
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                else
                {
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
	}
	
	public function createTaskFromParams($params)
	{
	
	}
	
	public static function prepareAddUser($person, $enabled = true)
	{
		$config["action"] = self::ActionAddUser;
                $config["user"] = $person;
                $config["enabled"] = $enabled;

		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareUpdateUser($person, $oldUsername, $enabled)
	{
		$config["action"] = self::ActionUpdateUser;
                $config["user"] = $person;
		$config["oldUsername"] = $oldUsername;
                $config["enabled"] = $enabled;
		
		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareDelUser($person)
	{
		$config["action"] = self::ActionDelUser;
                $config["user"] = $person;
                TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareAddUserToOu($person)
	{
		$config["action"] = self::ActionAddUserToOu;
                $config["user"] = $person;
                TaskQueue::insertNewTask($config, $person->getId());
	}
	
	public function getParams()
	{
	
	}
        
}

?>
