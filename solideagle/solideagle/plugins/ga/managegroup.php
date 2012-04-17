<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\StatusReport;
use solideagle\Config;

require_once('Zend/Loader.php');
\Zend_Loader::loadClass('Zend_Gdata_ClientLogin');
\Zend_Loader::loadClass('Zend_Gdata_Gapps');

class managegroup
{
    
    public static function addGroup($group)
    {
        $errorHandler = new errorhandler();
        
        $descriptorspec = array(
                        0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                        1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
                        2 => array("pipe", "w")   // stderr is a file to write to
        );
        $cmd = "gam create group " . $group->getName();
        if ($group->getDescription() != null)
            $cmd .= " description \"" . $group->getDescription() . "\"";
        
        var_dump($cmd);
        $proc_ls = proc_open($cmd, $descriptorspec, $pipes);

        while(true) 
        {   
            if(($buffer = fgets($pipes[1])) === false && ($error = fgets($pipes[2])) === false)
                break;

            if (substr($buffer, 0, 5) === 'Error')
                $errorHandler->addGappsError($buffer);
            
            if (isset($error) && $error != false)
                $errorHandler->addGappsError($error);
            
            echo $buffer;
            ob_flush();
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
