<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\ga\GamExecutor;
use solideagle\plugins\StatusReport;
use solideagle\Config;


class managegroup
{
    
    public static function addGroup($group)
    {
        $gamcmd = "create group \"" . $group->getName() . "\"";
        if ($ou->getDescription() != null)
            $gamcmd .= " description \"" . $group->getDescription() . "\"";
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    // rename not supported (todo)
    public static function updateGroup($group)
    {
        
    }

    public static function removeGroup($group)
    {
        $gamcmd = "delete group \"" . $group->getName() . "\"";
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
}

?>
