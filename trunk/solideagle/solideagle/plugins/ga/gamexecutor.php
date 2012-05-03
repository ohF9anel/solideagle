<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\StatusReport;

class GamExecutor
{
    public static function executeGamCommand($cmd)
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
