<?php

namespace solideagle\scripts\ad;

use solideagle\plugins\ad\managegroup;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;
use solideagle\data_access\Group;

use solideagle\logging\Logger;

class groupmanager implements TaskInterface
{
    const ActionAdd = "AddGroup";
    const ActionRemove = "RemoveGroup";
    const ActionRename = "RenameGroup";
    const ActionMove = "MoveGroup";

    public function runTask($taskqueue)
    {
        $config = $taskqueue->getConfiguration();

        if (!isset($config["action"]))
        {
            $taskqueue->setErrorMessages("Probleem met configuratie");
            return false;
        }

        if ($config["action"] == self::ActionAdd)
        {
            Logger::log("Trying to create group \"" . $config["group"]->getName() . "\" and make member of group \"" . $config["memberOfGroup"]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
            $ret = managegroup::addGroup($config["group"], $config["memberOfGroup"]);

            if($ret->isSucces())
            {
                    Logger::log("Successfully created group \"" . $config["group"]->getName() . "\" and made member of group \"" . $config["memberOfGroup"]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
                    return true;	
            }else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
            }
        }
        else if ($config["action"] == self::ActionRename && isset($config["newGroup"]) && isset($config["oldGroup"]))
        {
            Logger::log("Trying to rename group \"" . $config["oldGroup"]->getName() . "\" to \"" . $config["newGroup"]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
            $ret = managegroup::renameGroup($config["newGroup"], $config["oldGroup"]);

            if($ret->isSucces())
            {
                    Logger::log("Successfully renamed group \"" . $config["oldGroup"]->getName() . "\" to \"" . $config["newGroup"]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
                    return true;	
            }else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
            }
        }
        else if ($config["action"] == self::ActionRemove && isset($config["group"]))
        {
            Logger::log("Trying to remove group \"" . $config["group"]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
            $ret = managegroup::removeGroup($config["group"]);

            if($ret->isSucces())
            {
                    Logger::log("Successfully removed group \"" . $config["group"]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
                    return true;	
            }else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
            }
        }
        else if ($config["action"] == self::ActionMove && isset($config["group"])
                 && isset($config["newParent"]) && isset($config["newChildren"])
                 && isset($config["oldParent"]) && isset($config["oldChildren"]))
        {
            Logger::log("Trying to move group \"" . $config["group"]->getName() . "\" from parent group \"" . $config["oldParent"]->getName() . "\" to parent group \"" . $config["newParent"]->getName() . "\" in Active Directory.",PEAR_LOG_INFO);
            $ret = managegroup::moveGroup($config["group"],
                                          $config["newParent"], $config["newChildren"],
                                          $config["oldParent"], $config["oldChildren"]);

            if($ret->isSucces())
            {
                    return true;	
            }else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
            }
        }
        else
        {
            $taskqueue->setErrorMessages("Probleem met configuratie");
            return false; //it failed for some reason
        }

        $taskqueue->setErrorMessages("Probleem met configuratie");
        return false;
    }

    public static function prepareAddGroup($parents,$group)
    {
        $config["action"] = self::ActionAdd;
        $config["group"] = $group;
        $memberof = $parents;
        if ($memberof != null)
        {
            $memberof = is_array($memberof) ? $memberof[0] : $memberof;
        }
        
        $config["memberOfGroup"] = $memberof;

        TaskQueue::insertNewTask($config, $group->getId(), TaskQueue::TypeGroup);
    }
    
    public static function prepareRenameGroup($oldGroup, $newGroup)
    {
        $config["action"] = self::ActionRename;
        $config["oldGroup"] = $oldGroup;
        $config["newGroup"] = $newGroup;

        TaskQueue::insertNewTask($config, $oldGroup->getId(), TaskQueue::TypeGroup);
    }
    
    public static function prepareRemoveGroup($group)
    {
        $config["action"] = self::ActionRemove;
        $config["group"] = $group;

        TaskQueue::insertNewTask($config, $group->getId(), TaskQueue::TypeGroup);
    }
    
    public static function prepareMoveGroup($group, $newParent, $newChildren, $oldParent, $oldChildren)
    {
        $config["action"] = self::ActionMove;
        $config["group"] = $group;
        $config["newParent"] = $newParent;
        $config["newChildren"] = $newChildren;
        $config["oldParent"] = $oldParent;
        $config["oldChildren"] = $oldChildren;

        TaskQueue::insertNewTask($config, $group->getId(), TaskQueue::TypeGroup);
    }

}

?>
