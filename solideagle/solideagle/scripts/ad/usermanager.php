<?php
namespace solideagle\scripts\ad;

use solideagle\scripts\GlobalUserManager;

use solideagle\plugins\ad\ManageUser;

use solideagle\data_access\Group;
use solideagle\data_access\PlatformAD;

use solideagle\plugins\ad\User;
use solideagle\plugins\ad\ManageHomeFolder;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;

use solideagle\logging\Logger;


class usermanager implements TaskInterface
{
	const ActionAddUser = "AddUser";
	const ActionUpdateUser = "UpdateUser";
	const ActionDelUser = "DelUser";
        const ActionUpdatePassword = "UpdatePassword";
        const ActionMoveUser = "MoveUser";

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		// add user in ad
		if($config["action"] == self::ActionAddUser)
		{
			if (!isset($config["arrParentsGroups"]) || !isset($config["person"]) || !isset($config["enabled"]))
			{
				$taskqueue->setErrorMessages("Probleem met configuratie");
				return false;
			}
                        Logger::log("Trying to create user \"" . $config["person"]->getAccountUsername() . "\" in OU \"" . $config["arrParentsGroups"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
			 
			$userInfo = User::convertPersonToAdUser($config["person"], $config["enabled"])->getUserInfo();
			$ret = ManageUser::addUser($userInfo, $config["arrParentsGroups"]);

                        if($ret->isSucces())
                        {
                            Logger::log("Successfully created user \"" . $config["person"]->getAccountUsername() . "\" in OU \"" . $config["arrParentsGroups"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
                            $platformad = new PlatformAD();
                            $platformad->setPersonId($config["person"]->getId());
                            $platformad->setEnabled($config["enabled"]);

                            PlatformAD::addToPlatform($platformad);

                            GlobalUserManager::cleanPasswordIfAllAccountsExist($config["person"]->getId());

                            return true;	
                        }
                        else{
                            $taskqueue->setErrorMessages($ret->getError());
                            return false;
                        }
                }
                // update user in ad
                else if($config["action"] == self::ActionUpdateUser && isset($config["person"]) && isset($config["arrParentsGroups"]) && isset($config["enabled"]))
                {
                    Logger::log("Trying to update user \"" . $config["person"]->getAccountUsername() . "\" in OU \"" . $config["arrParentsGroups"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
                    $userInfo = User::convertPersonToAdUser($config["person"], $config["enabled"])->getUserInfo();
                    $ret = ManageUser::updateUser($userInfo, $config["arrParentsGroups"]);

                    if($ret->isSucces())
                    {
                        Logger::log("Successfully updated user \"" . $config["person"]->getAccountUsername() . "\" in OU \"" . $config["arrParentsGroups"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
                        $platformad = PlatformAD::getPlatformConfigByPersonId($config["person"]->getId());
                        $platformad->setEnabled($config["enabled"]);
                        PlatformAD::updatePlatform($platformad);
                        return true;	
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                // change password
                else if($config["action"] == self::ActionUpdatePassword && isset($config["username"]) && isset($config["password"]))
                {
                    Logger::log("Trying to update password of user \"" . $config["username"] . "\" in Active Directory.",PEAR_LOG_INFO);
                    $ret = ManageUser::changePassword($config["username"], $config["password"]);

                    if($ret->isSucces())
                    {
                        Logger::log("Successfully updated password of user \"" . $config["username"] . "\" in Active Directory.",PEAR_LOG_INFO);
                        return true;	
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                // delete user in ad
                else if($config["action"] == self::ActionDelUser && isset($config["person"]))
                {
                    Logger::log("Trying to remove user \"" . $config["person"]->getAccountUsername() . "\" in Active Directory.",PEAR_LOG_INFO);
                    $ret = ManageUser::delUser($config["person"]->getAccountUsername());

                    if($ret->isSucces())
                    {
                        Logger::log("Successfully removed user \"" . $config["person"]->getAccountUsername() . "\" in Active Directory.",PEAR_LOG_INFO);
                        PlatformAD::removePlatformByPersonId($config["person"]->getId());
                        return true;
                    }
                    else{
                        $taskqueue->setErrorMessages($ret->getError());
                        return false;
                    }
                }
                // move user in ad
                else if($config["action"] == self::ActionMoveUser && isset($config["person"]) && isset($config["newparents"]) && isset($config["oldparents"]))
                {
                    Logger::log("Trying to move user \"" . $config["person"]->getAccountUsername() . "\" from OU \"" . $config["oldparents"][0]->getName() . "\" to OU \"" . $config["newparents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
                    $userinfo = User::convertPersonToAdUser($config["person"])->getUserInfo();
                    $ret = ManageUser::moveUser($userinfo, $config['newparents'], $config['oldparents']);

                    if($ret->isSucces())
                    {
                        Logger::log("Successfully moved user \"" . $config["person"]->getAccountUsername() . "\" from OU \"" . $config["oldparents"][0]->getName() . "\" to OU \"" . $config["newparents"][0]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
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


	public static function prepareAddUser($person, $enabled = true)
	{
		$config["action"] = self::ActionAddUser;
		$config["person"] = $person;
		$config["enabled"] = $enabled;
		$config["arrParentsGroups"] = Group::getParents(Group::getGroupById($person->getGroupId()));

		TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareUpdateUser($person, $enabled)
	{
		$config["action"] = self::ActionUpdateUser;
		$config["person"] = $person;
		$config["enabled"] = $enabled;
		$config["arrParentsGroups"] = Group::getParents(Group::getGroupById($person->getGroupId()));

		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareChangePassword($person)
	{
		$config["action"] = self::ActionUpdatePassword;
                $config["username"] = $person->getAccountUsername();
                $config["password"] = $person->getAccountPassword();
                
                TaskQueue::insertNewTask($config, $person->getId());
	}

	public static function prepareDelUser($person)
	{
		$config["action"] = self::ActionDelUser;
		$config["person"] = $person;
		TaskQueue::insertNewTask($config, $person->getId());
	}
        
        public static function prepareMoveUser($person, $newgroup, $oldgroup)
        {
                $config["action"] = self::ActionMoveUser;
		$config["person"] = $person;
                
                $newparents = Group::getParents($newgroup);
                array_unshift($newparents, $newgroup);
		$config["newparents"] = $newparents;
                
                $oldparents = Group::getParents($oldgroup);
                array_unshift($oldparents, $oldgroup);
                $config["oldparents"] = $oldparents;

		TaskQueue::insertNewTask($config, $person->getId());
        }


}

?>
