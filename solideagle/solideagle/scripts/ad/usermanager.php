<?php
namespace solideagle\scripts\ad;

use solideagle\plugins\ad\ManageUser;

use solideagle\data_access\Group;
use solideagle\data_access\platforms;

use solideagle\plugins\ad\User;
use solideagle\plugins\ad\ManageHomeFolder;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class usermanager implements TaskInterface
{
	const ActionAddUser = 0;
        const ActionUpdateUser = 1;
        const ActionDelUser = 2;
        
        const taskId = 29;
        
        public function runTask($taskqueue)
	{
            $config = $taskqueue->getConfiguration();

            // add user in ad
            if($config["action"] == self::ActionAddUser)
            {
                if (!isset($config["arrParentsGroups"]) || !isset($config["person"]))
                {
                        $taskqueue->setErrorMessages("Probleem met configuratie");
                        return false;
                }
                   
                $userInfo = User::convertPersonToAdUser($config["person"])->getUserInfo();
                $ret = ManageUser::addUser($userInfo, $config["arrParentsGroups"]);

                if($ret->isSucces())
                {
                    $platform = new platforms();
                    $platform->setPlatformType(platforms::PLATFORM_AD);
                    $platform->setPersonId($config["person"]->getId());
                    $platform->setEnabled($config["person"]->getAccountActive());
                    platforms::addPlatform($platform);
                    return true;	
                }
                else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
                }
            }
            // update user in ad
            else if($config["action"] == self::ActionUpdateUser && isset($config["userInfo"]) && isset($config["arrParentsGroups"]))
            {
                $ret = ManageUser::updateUser($config["userInfo"],$config["arrParentsGroups"]);

                if($ret->isSucces())
                    {
                        return true;	
                    }
                else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
                }
            }
            // delete user in ad
            else if($config["action"] == self::ActionDelUser && isset($config["username"]))
            {
                $ret = ManageUser::delUser($config["username"]);

                if($ret->isSucces())
                {
                    return true;
                }
                else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
                }
            }
            // problem!
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
                $config["person"] = $person;
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
