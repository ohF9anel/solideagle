<?php

namespace AD;

require_once('Net/SSH2.php');
require_once 'logging/Logger.php';
require_once 'config.php';
use Logging\Logger;

class UploadFolder
{
    
    public static function createUploadFolder($server, $path, $username)
    {
        $conn = new \Net_SSH2($server);
        if (!$conn->login(S1_ADMINISTRATOR, AD_PASSWORD))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . AD_DC_HOST . ".", PEAR_LOG_ERR);
        }
        
        $conn->write("cmd\n");
        
        $conn->write("mkdir " . $path . "\\" . $username . "\n");
        $conn->write("icacls " . $path . "\\" . $username . " /q /deny " . AD_NETBIOS . "\\" . $username . ":(WDAC,WO,S)\n");
        $conn->write("icacls " . $path . "\\" . $username . " /q /grant " . AD_NETBIOS . "\\" . $username . ":M \n");
        $conn->write("*S-1-5-32-544:F *S-1-5-18:F \n");
        $conn->write("/inheritance:r /T /C \n");
        $conn->write("icacls " . $path . "\\" . $username . " /q /grant *S-1-5-11:(CI)(R,WD,AD) \n");
        
        $conn->write("exit\nexit\n");
        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
        $conn->disconnect();
    }
    
}

?>
