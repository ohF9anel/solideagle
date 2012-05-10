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
        if ($group->getDescription() != null)
            $gamcmd .= " description \"" . $group->getDescription() . "\"";
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function addGroupToGroup($childGroupName, $parentGroupName)
    {
        // clean chars not allowed in email address
        $cleanGroupName = \solideagle\data_access\helpers\UnicodeHelper::cleanEmailString($childGroupName);
        
        $email = $cleanGroupName . "@" . Config::singleton()->googledomain;
        $gamcmd = "update group \"" . $parentGroupName . "\" add member " . $email;
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function removeGroupFromGroup($childGroupName, $parentGroupName)
    {
        // clean chars not allowed in email address
        $cleanGroupName = \solideagle\data_access\helpers\UnicodeHelper::cleanEmailString($childGroupName);
        
        $email = $cleanGroupName . "@" . Config::singleton()->googledomain;
        $gamcmd = "update group \"" . $parentGroupName . "\" remove " . $email;
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    // rename not supported (todo?)
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
