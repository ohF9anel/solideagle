<?php

namespace AD;

require_once('Net/SSH2.php');
require_once 'logging/Logger.php';
require_once 'config.php';
use Logging\Logger;

class DownloadFolder
{
    public static function setDownloadFolder($server, $path, $username, $enabled = true)
    {
        $conn = new \Net_SSH2($server);
        if (!$conn->login(S1_ADMINISTRATOR, AD_PASSWORD))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . AD_DC_HOST . ".", PEAR_LOG_ERR);
        }
        
        $conn->write("cmd\n");
        
        if ($enabled)
        {
            // create download folder
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . "\n");

//            // deny some special permissions
//            $conn->write("icacls " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . " /q /deny " . AD_NETBIOS . "\\" . $username . ":(WDAC,WO,S)\n");
//
//            // allow modify
//            $conn->write("icacls " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . " /q /grant " . AD_NETBIOS . "\\" . $username . ":M *S-1-5-32-544:F *S-1-5-18:F /inheritance:r /T /C\n");
//
//            $conn->write("icacls " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . " /grant *S-1-5-11:(CI)(RX)\n");
//
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\Domain Users;s:n;p:read;i:so,sc\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . "\n");

            // make link
            $conn->write("mklink /j " . PATH_SHARE_DOWNLOADS . "\\" . $username . " " . $path . "\\" . $username . "\\" . DIR_NAME_DOWNLOADS . "\n");
        }
        else
        {
            $conn->write("rmdir " . PATH_SHARE_DOWNLOADS . "\\" . $username . " /s /q\n");
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
