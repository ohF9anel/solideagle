<?php
namespace solideagle\scripts\ad;


use solideagle\plugins\ad\ManageHomeFolder;
use solideagle\data_access\TaskInserter;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class usermanager implements TaskInterface
{
	const ActionAddUser = 0;
        const ActionUpdateUser = 1;
        const ActionAddHomeFolder = 2;
        
        const taskId = 29;
        
        public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if(!isset($config["action"]))
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}
		
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
			
			if($ret[0] === true)
			{
				return true;	
			}
                        else{
				$taskqueue->setErrorMessages($ret[1]);
				return false;
			}
                }
                else if($config["action"] == self::ActionAddHomeFolder)
                {
                        if (!isset($config["server"]) || !isset($config["username"]) || !isset($config["homeFolderPath"]) || !isset($config["scanSharePath"]) || !isset($config["downloadSharePath"]) || !isset($config["uploadSharePath"]) || !isset($config["wwwSharePath"]))
                        {
                                $taskqueue->setErrorMessages("Probleem met configuratie");
                                return false;
                        }
                        $mhf = new ManageHomeFolder($config["server"], $config["username"], $config["homeFolderPath"], $config["scanSharePath"], $config["wwwSharePath"], $config["downloadSharePath"], $config["uploadSharePath"]);
                        $mhf->startHomeFolderManager();
                        return true;
                }
                else
                {
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
		
		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}
	
	public function createTaskFromParams($params)
	{
	
	}
	
	public static function prepareAddUser($person)
	{
		$config["action"] = self::ActionAddUser;
                $config["userInfo"] = User::convertPersonToAdUser($person)->getUserInfo();
		$config["arrParentsGroups"] = Group::getParents(Group::getGroupById($person->getGroupId()));
		
                $taskInserter = new TaskInserter(self::taskId, $person->getId(), TaskInserter::TypePerson);
		$taskInserter->addToQueue($config);
	}
        
        public static function prepareUpdateUser($person)
	{
		$config["action"] = self::ActionUpdateUser;
                $config["userInfo"] = User::convertPersonToAdUser($person)->getUserInfo();
		$config["arrParentsGroups"] = Group::getParents(Group::getGroupById($person->getGroupId()));
		
                $taskInserter = new TaskInserter(self::taskId, $person->getId(), TaskInserter::TypePerson);
		$taskInserter->addToQueue($config);
	}
        
        public static function prepareAddHomeFolder($personId, $server, $username, $homeFolderPath, $scanSharePath, $wwwSharePath, $downloadSharePath, $uploadSharePath)
	{
		$config["action"] = self::ActionAddHomeFolder;
                $config["server"] = $server;
		$config["username"] = $username;
                $config["homeFolderPath"] = $homeFolderPath;
                $config["scanSharePath"] = $scanSharePath;
                $config["wwwSharePath"] = $wwwSharePath;
                $config["downloadSharePath"] = $downloadSharePath;
                $config["uploadSharePath"] = $uploadSharePath;
		
		$taskInserter = new TaskInserter(self::taskId, $personId, TaskInserter::TypePerson);
		$taskInserter->addToQueue($config);
	}	
	
	public function getParams()
	{
	
	}
        
}

?>
