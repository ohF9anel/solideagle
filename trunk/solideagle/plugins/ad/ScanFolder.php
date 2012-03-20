<?php

namespace AD;

require_once('Net/SSH2.php');
require_once 'logging/Logger.php';
require_once 'config.php';
use Logging\Logger;

class ScanFolder
{
    
    public static function setScanFolder($server, $path, $scanPath, $username, $enabled = true)
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
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\_scans\n");
            // access sys scan user
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\sys_scan_user;s:n;m:grant;p:read;w:dacl\" -on " . $path . "\\" . $username . "\_scans\n");
            // make link
            $conn->write("mklink /j " . $scanPath . "\\" . $username . ' ' . $path . "\\" . $username . "\\_scans\n");
        }
        else 
        {
            $conn->write("rmdir " . scanPath . "\\" . $username . " /s /q\n");
        }
        
        while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL))
{
	echo $data;
}
        
        $conn->write("exit\nexit\n");
        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
        $conn->disconnect();
    }
    
}

?>
