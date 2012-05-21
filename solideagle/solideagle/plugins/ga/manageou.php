<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\StatusReport;
use solideagle\Config;


class manageou
{
    
    public static function addOU($ou, $parentOus)
    {
        $gamcmd = "create org \"" . $ou->getName() . "\"";
        if ($ou->getDescription() != null)
            $gamcmd .= " description \"" . $ou->getDescription() . "\"";
        if ($parentOus != null)
        {
            $gamcmd .= " parent \"";
            for($i = sizeof($parentOus) - 1; $i >= 0; $i--)
            {
                $gamcmd .= $parentOus[$i]->getName() . "/";
            }
            $gamcmd .= "\"";
        }
         
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function moveOU($ou, $oldParentOus, $newParentOus)
    {
        $cmd = "update org \"";
        
        // ou to move
        if ($oldParentOus != null)
        {
            for($i = sizeof($oldParentOus) - 1; $i >= 0; $i--)
            {
                $cmd .= $oldParentOus[$i]->getName() . "/";
            }
        }
        $cmd .= $ou->getName() . "\"";
        
        // move to parent
        if ($newParentOus != null)
        {
            $cmd .= " parent \"";
            for($i = sizeof($newParentOus) - 1; $i >= 0; $i--)
            {
                $cmd .= $newParentOus[$i]->getName() . "/";
            }
        }
        
        $cmd .= "\"";
        
        $report = GamExecutor::executeGamCommand($cmd);
        
        return $report;
    }
    
    public static function updateOU($oldGroup, $newGroup, $parentOus)
    {
        $cmd = "update org \"";
        
        // ou to update
        if ($parentOus != null)
        {
            for($i = sizeof($parentOus) - 1; $i >= 0; $i--)
            {
                $cmd .= $parentOus[$i]->getName() . "/";
            }
        }
        $cmd .= $oldGroup->getName() . "\"";

        // rename ou?
        $cmd .= " name \"" . $newGroup->getName() . "\"";
        
        // set new description?
        $cmd .= " description \"" . $newGroup->getDescription() . "\"";
        
        $report = GamExecutor::executeGamCommand($cmd);
        
        return $report;
    }
    
    public static function removeOU($ou, $parentOus)
    {
        $cmd = " delete org \"";
        
        // ou to delete
        if ($parentOus != null)
        {
            for($i = sizeof($parentOus) - 1; $i >= 0; $i--)
            {
                $cmd .= $parentOus[$i]->getName() . "/";
            }
        }
        $cmd .= $ou->getName() . "\"";
        
        $report = GamExecutor::executeGamCommand($cmd);
        
        return $report;
    }
    
}

?>
