<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\ga\GamExecutor;
use solideagle\plugins\StatusReport;
use solideagle\Config;
use solideagle\data_access\helpers\UnicodeHelper;


class managegroup
{
    
    public static function addGroup($name,$mail)
    {
        $gamcmd = "create group \"" . $mail . "\"";
        $gamcmd .= " name \"" . $name . "\"";
        
        /*if ($group->getDescription() != null)
            $gamcmd .= " description \"" . $group->getDescription() . "\"";*/
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function addGroupToGroup($childGroupName, $parentGroupName)
    {
        $gamcmd = "update group \"" . $parentGroupName . "\" add member " . $childGroupName;
        $report = GamExecutor::executeGamCommand($gamcmd);
        return $report;
    }
    
    public static function removeGroupFromGroup($childGroupName, $parentGroupName)
    {
        $gamcmd = "update group \"" . $parentGroupName . "\" remove " . $childGroupName;
        $report = GamExecutor::executeGamCommand($gamcmd);
        return $report;
    }
    
    // rename not supported (todo?)
    public static function updateGroup($group)
    {
        
    }

    public static function removeGroup($group)
    {
        $gamcmd = "delete group \"" . $group . "\"";
        $report = GamExecutor::executeGamCommand($gamcmd);
        return $report;
    }
    
}

?>
