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
        
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "w")   // stderr is a file to write to
//        );
//        $cmd = "gam create org " . $ou->getName();
//        if ($ou->getDescription() != null)
//            $cmd .= " description \"" . $ou->getDescription() . "\"";
//        if ($parentOus != null)
//        {
//            $cmd .= " parent ";
//            for($i = sizeof($parentOus) - 1; $i >= 0; $i--)
//            {
//                $cmd .= $parentOus[$i]->getName() . "/";
//            }
//        }
//       // var_dump($cmd);
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false && ($error = fgets($pipes[2])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error')
//                $errorHandler->addGappsError($buffer);
//            
//            if (isset($error) && $error != false)
//                $errorHandler->addGappsError($error);
//            
//           // echo $buffer;
//           // ob_flush();
//            //flush();
//        }
//
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//        //var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public static function moveOU($ou, $oldParentOus, $newParentOus)
    {
        $cmd = "update org ";
        
        // ou to move
        if ($oldParentOus != null)
        {
            for($i = sizeof($oldParentOus) - 1; $i >= 0; $i--)
            {
                $cmd .= $oldParentOus[$i]->getName() . "/";
            }
        }
        $cmd .= $ou->getName();
        
        // move to parent
        if ($newParentOus != null)
        {
            $cmd .= " parent ";
            for($i = sizeof($newParentOus) - 1; $i >= 0; $i--)
            {
                $cmd .= $newParentOus[$i]->getName() . "/";
            }
        }
        
        $report = GamExecutor::executeGamCommand($cmd);
        
        return $report;
        
        
        
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "a")   // stderr is a file to write to
//        );
//        
//        $cmd = "gam update org ";
//        
//        // ou to move
//        if ($oldParentOus != null)
//        {
//            for($i = sizeof($oldParentOus) - 1; $i >= 0; $i--)
//            {
//                $cmd .= $oldParentOus[$i]->getName() . "/";
//            }
//        }
//        $cmd .= $ou->getName();
//        
//        // move to parent
//        if ($newParentOus != null)
//        {
//            $cmd .= " parent ";
//            for($i = sizeof($newParentOus) - 1; $i >= 0; $i--)
//            {
//                $cmd .= $newParentOus[$i]->getName() . "/";
//            }
//        }
//        
//      //  var_dump($cmd);
//
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false && ($error = fgets($pipes[2])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error')
//                $errorHandler->addGappsError($buffer);
//            
//            if (isset($error) && $error != false)
//                $errorHandler->addGappsError($error);
//            
//           // echo $buffer;
//           // ob_flush();
//           // flush();
//        }
//
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//       // var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
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

        var_dump($cmd);
        
        $report = GamExecutor::executeGamCommand($cmd);
        
        return $report;
        
//        
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "a")   // stderr is a file to write to
//        );
//        
//        $cmd = "gam update org ";
//        
//        // ou to update
//        if ($parentOus != null)
//        {
//            for($i = sizeof($parentOus) - 1; $i >= 0; $i--)
//            {
//                $cmd .= $parentOus[$i]->getName() . "/";
//            }
//        }
//        $cmd .= $oldGroup->getName();
//
//        // rename ou?
//        $cmd .= " name " . $newGroup->getName();
//        
//        // set new description?
//        $cmd .= " description \"" . $newGroup->getDescription() . "\"";
//        
//       // var_dump($cmd);
//
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false && ($error = fgets($pipes[2])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error')
//                $errorHandler->addGappsError($buffer);
//            
//            if (isset($error) && $error != false)
//                $errorHandler->addGappsError($error);
//            
//            echo $buffer;
//            ob_flush();
//            flush();
//        }
//
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//       // var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
    public static function removeOU($ou, $parentOus)
    {
        $cmd = " delete org ";
        
        // ou to delete
        if ($parentOus != null)
        {
            for($i = sizeof($parentOus) - 1; $i >= 0; $i--)
            {
                $cmd .= $parentOus[$i]->getName() . "/";
            }
        }
        $cmd .= $ou->getName();
        
        $report = GamExecutor::executeGamCommand($cmd);
        
        return $report;
        
//        $errorHandler = new errorhandler();
//        
//        $descriptorspec = array(
//                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
//                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
//                        2 => array("pipe", "w")   // stderr is a file to write to
//        );
//        
//        $cmd = "gam delete org ";
//        
//        // ou to delete
//        if ($parentOus != null)
//        {
//            for($i = sizeof($parentOus) - 1; $i >= 0; $i--)
//            {
//                $cmd .= $parentOus[$i]->getName() . "/";
//            }
//        }
//        $cmd .= $ou->getName();
//        
//       // var_dump($cmd);
//        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);
//
//        while(true) 
//        {   
//            if(($buffer = fgets($pipes[1])) === false && ($error = fgets($pipes[2])) === false)
//                break;
//
//            if (substr($buffer, 0, 5) === 'Error' || substr($buffer, 0, 12) === 'Not Deleted.')
//                $errorHandler->addGappsError($buffer);
//            
//            if (isset($error) && $error != false)
//                $errorHandler->addGappsError($error);
//            
//            echo $buffer;
//            ob_flush();
//            flush();
//        }
//
//        foreach ($pipes as $pipe)
//            fclose($pipe);
//
//        proc_close($proc_ls);
//
//       // var_dump($errorHandler->toString());
//        
//        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
}

?>
