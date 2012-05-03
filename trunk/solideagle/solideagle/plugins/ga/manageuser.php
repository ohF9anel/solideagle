<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\StatusReport;
use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\Config;

class manageuser
{
    public static function addUser($person, $enabled = true)
    {
        $gamcmd = 'create user ' . $person->getAccountUsername() . ' firstname ' . $person->getFirstName() . 
                 ' lastname ' . $person->getName() . ' password ' . $person->getAccountPassword();
        if (!$enabled)
            $gamcmd .= " suspended on ";
        $report = self::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function addUserToOu($person)
    {
        $gamcmd = "update org ";
        
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
        $gamcmd .= " add " . $person->getAccountUsername();
        
        $report = self::executeGamCommand($gamcmd);
        
        return $report;
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "a")   // stderr is a file to write to
//        );
//        
//        // add user to ou
//        $cmd = "gam update org ";
//        
//        $childou = Group::getGroupById($person->getGroupId());
//        $parentous = Group::getParents($childou);
//        if ($parentous != null)
//        {
//            for($i = sizeof($parentous) - 1; $i >= 0; $i--)
//            {
//                $cmd .= $parentous[$i]->getName() . "/";
//            }
//        }
//        $cmd .= $childou->getName();
//        $cmd .= " add " . $person->getAccountUsername();
//        
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error')
//                $errorHandler->addGappsError($buffer);
//            
//            echo $buffer;
//            flush();
//        }
//        
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//        var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public static function updateUser($person, $enabled)
    {
        $gamcmd = "update user " . $person->getAccountUsername() . " ";
        $gamcmd .= "firstname " . $person->getFirstName() . " ";
        $gamcmd .= "lastname " . $person->getName() . " ";
        $gamcmd .= "password " . $person->getAccountPassword() . " ";

        if (!$enabled)
            $gamcmd .= "suspended on";
        else
            $gamcmd .= "suspended off";
        
        $report = self::executeGamCommand($gamcmd);
        
        return $report;
        
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "a")   // stderr is a file to write to
//        );
//        $cmd = "gam update user " . $person->getAccountUsername() . " ";
//        $cmd .= "username " . $person->getAccountUsername() . " ";
//        $cmd .= "firstname " . $person->getFirstName() . " ";
//        $cmd .= "lastname " . $person->getName() . " ";
//        $cmd .= "password " . $person->getAccountPassword() . " ";
//        
//        if (!$enabled)
//            $cmd .= "suspended on";
//        else
//            $cmd .= "suspended off";
//
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error')
//                $errorHandler->addGappsError($buffer);
//            
//            echo $buffer;
//            flush();
//        }
//
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//        var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public static function updatePassword($username, $password)
    {
        $gamcmd = "update user " . $username . " ";
        $gamcmd .= "password " . $password . " ";
        
        $report = self::executeGamCommand($gamcmd);
        
        return $report;
    }
    
    public static function removeUser($person)
    {
        $gamcmd = "delete user " . $person->getAccountUsername();
        
        $report = self::executeGamCommand($gamcmd);
        
        return $report;
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "a")   // stderr is a file to write to
//        );
//        
//        $cmd = "gam delete user " . $person->getAccountUsername();
//
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error')
//                $errorHandler->addGappsError($buffer);
//            
//            echo $buffer;
//            flush();
//        }
//
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//        var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public function setPhoto($username, $filepath)
    {
        $gamcmd = "user " . $username . " update photo " . $filepath;
        
        $report = self::executeGamCommand($gamcmd);
        
        return $report;
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "a")   // stderr is a file to write to
//        );
//        
//        $cmd = "gam user " . $username . " update photo " . $filepath;
//
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error')
//                $errorHandler->addGappsError($buffer);
//            
//            echo $buffer;
//            flush();
//        }
//
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//        var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public function downloadTempFile($url, $path)
    {
        ob_start();
        passthru('wget ' . $url . ' ' . $path . ' 2>&1');
        $out = ob_get_contents();
        ob_end_clean();
        if (preg_match('/saving to:.{4}([a-z0-9\.-_]*)/i', $out, $filename)) {
            return $filename;
        } else {
            return "no match";
        }
    }
    
    private static function executeGamCommand($cmd)
    {
        $errorHandler = new errorhandler();
        
        ob_start();
        $cmd = 'python ../../gam/gam.py ' . $cmd;
        
        passthru($cmd);
        $out = ob_get_contents();
        ob_end_clean();

        foreach(preg_split("/(\r?\n)/", $out) as $key => $line)
        {
            //if ($key == 0) continue;
            if (preg_match('/^ERROR/i', $line, $matches))
                $errorHandler->addGappsError($line);   
        }
        
        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
            
}

?>
