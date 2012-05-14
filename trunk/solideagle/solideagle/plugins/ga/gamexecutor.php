<?php

namespace solideagle\plugins\ga;

use solideagle\plugins\StatusReport;

class GamExecutor
{
    public static function executeGamCommand($cmd)
    {
        $errorHandler = new errorhandler();

        ob_start();
        $cmd = 'python ../../gam/gam.py ' . $cmd . ' 2>&1';

        passthru($cmd);
        $out = ob_get_contents();
        ob_end_clean();  

        foreach(preg_split("/(\r?\n)/", $out) as $key => $line)
        {
            //if ($key == 0) continue;
            //if (preg_match('/^ERROR/i', $line, $matches) || preg_match('/^IOERROR/i', $line, $matches))
            if (stripos($line,'ERROR') !== false)
            {
                if (($pos = stripos($line, 'reason')) !== false)
                {
                    if(($pos = stripos($line, 'reason=')) !== false)
                    {
                        $substr = substr($line, $pos + 8);
                        $endquote = strpos($substr, "\"");
                        $errorHandler->addGappsError (substr($line, $pos + 8, $endquote));
                    }
                    else if(($pos = stripos($line, 'reason')) !== false)
                    {
                        $substr = substr($line, $pos + 10);
                        $endquote = strpos($substr, '\'');
                        $errorHandler->addGappsError(substr($substr, 0, $endquote));
                    }
                }
                else
                    $errorHandler->addGappsError($line);
            }
            
        }
        return new StatusReport(!$errorHandler->hasErrors(), $errorHandler->toString());
    }
    
}
?>
