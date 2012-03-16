<?php

namespace AD;

include('Net/SSH2.php');
require_once 'logging/Logger.php';
require_once 'config.php';
use Logging\Logger;

class HomeFolder
{
    public static function CreateHomeFolder($server, $path, $username, $arrReadRightsGroups)
    {
        $conn = new \Net_SSH2($server);
        if (!$conn->login(S1_ADMINISTRATOR, AD_PASSWORD))
        {
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . $server . ".", PEAR_LOG_ERR);
            return false;
        }
        echo '<pre>';
        
        $conn->write("cmd\n");
        
        echo $conn->read(">");
        
        $conn->write("mkdir " . $path . "\\" . $username . "\n");
        
        echo $conn->read('>');
        
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "www\n");
        
        echo $conn->read('>');
        
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "scans\n");
        
        echo $conn->read('>');
        
        $conn->write("icacls " . $path . "\\" . $username . " /q /reset /t\n");
        echo $conn->read('>');
        
        $conn->write("takeown /F " . $path . "\\" . $username . " /A /R /D Y\n");
        echo $conn->read('>');
        
        $conn->write("takeown /F " . $path . "\\" . $username . "\\*.* /A /R /D Y\n");
        echo $conn->read('>');
        
        $conn->write("icacls " . $path . "\\" . $username . " /q /grant ^ \n");
        echo $conn->read('>');
        
        $conn->write("\n");
        echo $conn->read('>');
        
        echo '</pre>';
        //var_dump($conn->read('ENDOFSESSION'));
        //var_dump($conn->read('C'));
        //var_dump($conn->exec('cmd /C' . $cmd2));
//          var_dump($ssh->exec('cmd /Cecho %mainPath%))
        
    }
}

?>
