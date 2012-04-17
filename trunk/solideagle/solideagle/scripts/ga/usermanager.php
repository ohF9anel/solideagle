<?php
namespace solideagle\scripts\ga;

use solideagle\data_access\Person;

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

		if($config["action"] == self::ActionAddUser && isset($config["user"]))
		{  
                    $ret = manageuser::addUser($config["user"]);
                        
                    if($ret->isSucces())
                    {
                        return true;	
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                else if($config["action"] == self::ActionUpdateUser && isset($config["user"]) && isset($config["oldUsername"]))
                {
                    $ret = manageuser::updateUser($config["user"], $config["oldUsername"]);
                    
                    if($ret->isSucces())
                    {
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
	
	public static function prepareAddUser($person)
	{
		$config["action"] = self::ActionAddUser;
                $config["user"] = $person;

		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareUpdateUser($person, $oldUsername)
	{
		$config["action"] = self::ActionUpdateUser;
                $config["user"] = $person;
		$config["oldUsername"] = $oldUsername;
		
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
