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
        $errorHandler = new errorhandler();
        
        $descriptorspec = array(
                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "a")   // stderr is a file to write to
        );
        $cmd = "gam create user " . $person->getAccountUsername() . " ";
        $cmd .= "firstname " . $person->getFirstName() . " ";
        $cmd .= "lastname " . $person->getName() . " ";
        $cmd .= "password " . $person->getAccountPassword();
        
        if (!$enabled)
            $cmd .= " suspended on";
        
        
        // add user
        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);

        while(true) 
        {   
            if(($buffer = fgets($pipes[1])) === false)
                break;

            if (substr($buffer, 0, 5) === 'Error')
                $errorHandler->addGappsError($buffer);
            
            echo $buffer;
            flush();
        }
        
        foreach ($pipes as $pipe)
            fclose($pipe);

        proc_close($proc_ls);

        var_dump($errorHandler->toString());
        
        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public static function addUserToOu($person)
    {
        $errorHandler = new errorhandler();
        
        $descriptorspec = array(
                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "a")   // stderr is a file to write to
        );
        
        // add user to ou
        $cmd = "gam update org ";
        
        $childou = Group::getGroupById($person->getGroupId());
        $parentous = Group::getParents($childou);
        if ($parentous != null)
        {
            for($i = sizeof($parentous) - 1; $i >= 0; $i--)
            {
                $cmd .= $parentous[$i]->getName() . "/";
            }
        }
        $cmd .= $childou->getName();
        $cmd .= " add " . $person->getAccountUsername();
        
        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);

        while(true) 
        {   
            if(($buffer = fgets($pipes[1])) === false)
                break;

            if (substr($buffer, 0, 5) === 'Error')
                $errorHandler->addGappsError($buffer);
            
            echo $buffer;
            flush();
        }
        
        foreach ($pipes as $pipe)
            fclose($pipe);

        proc_close($proc_ls);

        var_dump($errorHandler->toString());
        
        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public static function updateUser($person, $enabled)
    {
        $errorHandler = new errorhandler();
        
        $descriptorspec = array(
                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "a")   // stderr is a file to write to
        );
        $cmd = "gam update user " . $person->getAccountUsername() . " ";
        $cmd .= "username " . $person->getAccountUsername() . " ";
        $cmd .= "firstname " . $person->getFirstName() . " ";
        $cmd .= "lastname " . $person->getName() . " ";
        $cmd .= "password " . $person->getAccountPassword() . " ";
        
        if (!$enabled)
            $cmd .= "suspended on";
        else
            $cmd .= "suspended off";

        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);

        while(true) 
        {   
            if(($buffer = fgets($pipes[1])) === false)
                break;

            if (substr($buffer, 0, 5) === 'Error')
                $errorHandler->addGappsError($buffer);
            
            echo $buffer;
            flush();
        }

        foreach ($pipes as $pipe)
            fclose($pipe);

        proc_close($proc_ls);

        var_dump($errorHandler->toString());
        
        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public static function removeUser($person)
    {
        $errorHandler = new errorhandler();
        
        $descriptorspec = array(
                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "a")   // stderr is a file to write to
        );
        
        $cmd = "gam delete user " . $person->getAccountUsername();

        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);

        while(true) 
        {   
            if(($buffer = fgets($pipes[1])) === false)
                break;

            if (substr($buffer, 0, 5) === 'Error')
                $errorHandler->addGappsError($buffer);
            
            echo $buffer;
            flush();
        }

        foreach ($pipes as $pipe)
            fclose($pipe);

        proc_close($proc_ls);

        var_dump($errorHandler->toString());
        
        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public function setPhoto($username, $filepath)
    {
        $errorHandler = new errorhandler();
        
        $descriptorspec = array(
                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "a")   // stderr is a file to write to
        );
        
        $cmd = "gam user " . $person->getAccountUsername() . " update photo " . $filepath;

        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);

        while(true) 
        {   
            if(($buffer = fgets($pipes[1])) === false)
                break;

            if (substr($buffer, 0, 5) === 'Error')
                $errorHandler->addGappsError($buffer);
            
            echo $buffer;
            flush();
        }

        foreach ($pipes as $pipe)
            fclose($pipe);

        proc_close($proc_ls);

        var_dump($errorHandler->toString());
        
        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
            
}

?>
