<?php

namespace solideagle\plugins\ad;

use solideagle\logging\Logger;
use solideagle\Config;

class DownloadFolder
{
    public static function setDownloadFolder($server, $path, $downloadSharePath, $username, $enabled = true)
    {
        $conn = new \Net_SSH2($server);
        if (!$conn->login(Config::$ad_administrator, Config::$ad_password))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . Config::$ad_dc_host . ".", PEAR_LOG_ERR);
        }
        
        $conn->write("cmd\n");
        
        if ($enabled)
        {
            // create download folder
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . Config::$dir_name_downloads . "\n");

            // deny some special permissions
            $conn->write("icacls " . $path . "\\" . $username . "\\" . Config::$dir_name_downloads . " /q /deny " . Config::$ad_netbios . "\\" . $username . ":(WDAC,WO,S)\n");

            // allow modify
            $conn->write("icacls " . $path . "\\" . $username . "\\" . Config::$dir_name_downloads . " /q /grant " . Config::$ad_netbios . "\\" . $username . ":M *S-1-5-32-544:F *S-1-5-18:F /inheritance:r /T /C\n");

            $conn->write("icacls " . $path . "\\" . $username . "\\" . Config::$dir_name_downloads . " /grant *S-1-5-11:(CI)(RX)\n");

            $conn->write("setacl -ot file -actn ace -ace \"n:" . Config::$ad_netbios . "\\Domain Users;s:n;p:read;i:so,sc\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_downloads . "\n");

            // make link
            $conn->write("mklink /j " . $downloadSharePath . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . Config::$dir_name_downloads . "\n");
        }
        else
        {
            $conn->write("rmdir " . $downloadSharePath . "\\" . $username . " /s /q\n");
        }
        
//        while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL))
//{
//	echo $data;
//}
        $conn->write("exit\nexit\n");
        $conn->write("echo ENDOFCODE");
        $conn->read('ENDOFCODE');
        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
        $conn->disconnect();
    }
}

?>
