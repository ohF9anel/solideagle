<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\ga\GamExecutor;
use solideagle\plugins\StatusReport;
use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\data_access\helpers\UnicodeHelper;
use solideagle\Config;

class manageuser
{
    public static function addUser($person, $enabled = true)
    {
        $gamcmd = "create user " . $person->getAccountUsername() . " firstname \"" . $person->getFirstName() . 
                 "\" lastname \"" . $person->getName() . "\" password \"" . $person->getAccountPassword() . "\"";
        if (!$enabled)
            $gamcmd .= " suspended on ";
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function addUserToGroup($groupname, $username)
    {
        $email = $username . "@" . Config::singleton()->googledomain;
        $gamcmd = "update group \"" . $groupname . "\" add member " . $email;
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function removeUserFromGroup($groupname, $username)
    {
        $email = $username . "@" . Config::singleton()->googledomain;
        $gamcmd = "update group \"" . $groupname . "\" remove " . $email;
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function addUserToOu($person)
    {
        $gamcmd = "update org \"";
        
        $childou = Group::getGroupById($person->getGroupId());
        $parentous = Group::getParents($childou);
        if ($parentous != null)
        {
            for($i = sizeof($parentous) - 1; $i >= 0; $i--)
            {
                $gamcmd .= $parentous[$i]->getName() . "/";
            }
        }
        $gamcmd .= $childou->getName();
        $gamcmd .= "\" add " . $person->getAccountUsername(); 
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function updateUser($person, $enabled)
    {
        $gamcmd = "update user " . $person->getAccountUsername() . " ";
        $gamcmd .= "firstname \"" . $person->getFirstName() . "\" ";
        $gamcmd .= "lastname \"" . $person->getName() . "\" ";

        if (!$enabled)
            $gamcmd .= "suspended on";
        else
            $gamcmd .= "suspended off";
        
        $report = GamExecutor::executeGamCommand($gamcmd);
       
        return $report;
    }
    
    public static function updatePassword($username, $password)
    {
        $gamcmd = "update user " . $username . " ";
        $gamcmd .= "password " . $password . " ";
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function removeUser($person)
    {
        $gamcmd = "delete user " . $person->getAccountUsername();
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public function setPhoto($username, $filepath)
    {
        $gamcmd = "user " . $username . " update photo " . $filepath;
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function setEmailSignature($username, $signature)
    {
        $gamcmd = "user " . $username . " signature \"" . $signature . "\"";
        
        $report = GamExecutor::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function setAlias($username, $firstname, $lastname)
    {
        for($i = 0; $i < 10; $i++)
        {
            $alias = UnicodeHelper::cleanEmailString($firstname) . "." . UnicodeHelper::cleanEmailString($lastname);
            
            if ($i != 0)
                $alias .= $i;

            $gamcmd = "create nickname " . $alias . " user \"" . $username . "\"";

            $report = GamExecutor::executeGamCommand($gamcmd);

            if(!$report->isSucces() && $report->getError() == "EntityExists")
                continue;
            
            break;
        }
        
        return $report;
    }
    
}

?>
