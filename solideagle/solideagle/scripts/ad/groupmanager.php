<?php

namespace solideagle\scripts\ad;

use solideagle\plugins\ad\managegroup;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;
use solideagle\data_access\Group;

class groupmanager implements TaskInterface
{
    const ActionAdd = 0;
    
    const taskId = 31;

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

            if ($ret[0] === true)
            {
                return true;
            }
            else
            {
                $taskqueue->setErrorMessages($ret[1]);
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

    public function getParams()
    {
        
    }

}

?>
