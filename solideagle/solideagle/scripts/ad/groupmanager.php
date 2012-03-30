<?php

namespace solideagle\scripts\ad;

use solideagle\plugins\ad\managegroup;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;
use solideagle\data_access\Group;

class groupmanager implements TaskInterface
{
    const ActionAdd = 0;
    const ActionRemove = 1;
    const ActionRename = 2;
    const ActionMove = 3;

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
            $ret = managegroup::addGroup($config["group"], $config["memberOfGroup"]);

            if($ret->isSucces())
            {
                    return true;	
            }else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
            }
        }
        else if ($config["action"] == self::ActionRename && isset($config["newGroup"]) && isset($config["oldGroup"]))
        {
            $ret = managegroup::renameGroup($config["newGroup"], $config["oldGroup"]);

            if($ret->isSucces())
            {
                    return true;	
            }else{
                    $taskqueue->setErrorMessages($ret->getError());
                    return false;
            }
        }
        else if ($config["action"] == self::ActionRemove && isset($config["group"]))
        {
            $ret = managegroup::removeGroup($config["group"]);

            if($ret->isSucces())
            {
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

    public function createTaskFromParams($params)
    {
        
    }

    public static function prepareAddGroup($group)
    {
        $config["action"] = self::ActionAdd;
        $config["group"] = $group;
        $memberof = Group::getParents($group);
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
    

    public function getParams()
    {
        
    }

}

?>
