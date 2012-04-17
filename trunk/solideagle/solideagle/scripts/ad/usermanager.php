<?php
namespace solideagle\scripts\ad;


use solideagle\plugins\ad\ManageUser;

use solideagle\data_access\Group;

use solideagle\plugins\ad\User;
use solideagle\plugins\ad\ManageHomeFolder;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class usermanager implements TaskInterface
{
	const ActionAddUser = 0;
        const ActionUpdateUser = 1;
        const ActionDelUser = 2;
        //const ActionAddHomeFolder = 3;
        
        const taskId = 29;
        
        public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddUser || $config["action"] == self::ActionUpdateUser)
		{
                        if (!isset($config["userInfo"]) || !isset($config["arrParentsGroups"]))
                        {
                                $taskqueue->setErrorMessages("Probleem met configuratie");
                                return false;
                        }
                        if($config["action"] == self::ActionAddUser)
                        {
                            $ret = ManageUser::addUser($config["userInfo"],$config["arrParentsGroups"]);
                        }
                        else if ($config["action"] == self::ActionUpdateUser)
                        {
                            $ret = ManageUser::updateUser($config["userInfo"],$config["arrParentsGroups"]);
                        }
                        else if ($config["action"] == self::ActionDelUser)
                        {
                            $ret = ManageUser::delUser($config["username"]);
                        }
			if($ret->isSucces())
			{
                            return true;	
			}
                        else{
                            $taskqueue->setErrorMessages($ret->getError());
                            return false;
			}
                }
                else if($config["action"] == self::ActionDelUser && isset($config["username"]))
                {
                    if ($config["action"] == self::ActionDelUser)
                    {
                        $ret = ManageUser::delUser($config["username"]);
                    }
                    if($ret->isSucces())
                    {
                        return true;
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
//                else if($config["action"] == self::ActionAddHomeFolder && isset($config["server"]) && isset($config["username"]) && isset($config["homeFolderPath"]) && isset($config["scanSharePath"]) && isset($config["downloadSharePath"]) && isset($config["uploadSharePath"]) && isset($config["wwwSharePath"]))
//                {
//                        $mhf = new ManageHomeFolder($config["server"], $config["username"], $config["homeFolderPath"], $config["scanSharePath"], $config["wwwSharePath"], $config["downloadSharePath"], $config["uploadSharePath"]);
//                        $mhf->startHomeFolderManager();
//                        return true;
//                }
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
                $config["userInfo"] = User::convertPersonToAdUser($person)->getUserInfo();
		$config["arrParentsGroups"] = Group::getParents(Group::getGroupById($person->getGroupId()));

		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareUpdateUser($person)
	{
		$config["action"] = self::ActionUpdateUser;
                $config["userInfo"] = User::convertPersonToAdUser($person)->getUserInfo();
		$config["arrParentsGroups"] = Group::getParents(Group::getGroupById($person->getGroupId()));
		
		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareDelUser($person)
	{
		$config["action"] = self::ActionDelUser;
                $config["username"] = $person->getAccountUsername();
              TaskQueue::insertNewTask($config, $person->getId());
	}
        
//        public static function prepareAddHomeFolder($personId, $server, $username, $homeFolderPath, $scanSharePath, $wwwSharePath, $downloadSharePath, $uploadSharePath)
//	{
//		$config["action"] = self::ActionAddHomeFolder;
//                $config["server"] = $server;
//				$config["username"] = $username;
//                $config["homeFolderPath"] = $homeFolderPath;
//                $config["scanSharePath"] = $scanSharePath;
//                $config["wwwSharePath"] = $wwwSharePath;
//                $config["downloadSharePath"] = $downloadSharePath;
//                $config["uploadSharePath"] = $uploadSharePath;
//		
//	TaskQueue::insertNewTask($config, $personId);
//	}	
	
	public function getParams()
	{
	
	}
        
}

?>
