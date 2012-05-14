<?php

namespace solideagle\plugins\ad;

use solideagle\logging\Logger;
use solideagle\Config;

class DownloadFolder
{
    public static function setDownloadFolder($conn, $path, $downloadSharePath, $username, $enabled = true)
    {
       
        
        if ($enabled)
        {
            // create download folder
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_downloads . "\n");

            // deny some special permissions
            $conn->write("icacls " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_downloads . " /q /deny " . Config::singleton()->ad_dns . "\\" . $username . ":(WDAC,WO,S)\n");

            // allow modify
            $conn->write("icacls " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_downloads . " /q /grant " . Config::singleton()->ad_dns . "\\" . $username . ":M *S-1-5-32-544:F *S-1-5-18:F /inheritance:r /T /C\n");

            $conn->write("icacls " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_downloads . " /grant *S-1-5-11:(CI)(RX)\n");

            $conn->write("setacl -ot file -actn ace -ace \"n:" . Config::singleton()->ad_dns . "\\Domain Users;s:n;p:read;i:so,sc\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_downloads . "\n");

            // make link
            $conn->write("mklink /j " . $downloadSharePath . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_downloads . "\n");
        }
        else
        {
            $conn->write("rmdir " . $downloadSharePath . "\\" . $username . " /s /q\n");
        }
        
//        $conn->write("echo ENDOFCODE\n");
//        $conn->read("ENDOFCODE");
//        while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL))
//{
//	echo $data;
//}
     

    }
}

?>
