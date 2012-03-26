<?php

namespace AD;

require_once('Net/SSH2.php');
require_once 'logging/Logger.php';
require_once 'config.php';
use Logging\Logger;

class ScanFolder
{
    
    public static function setScanFolder($server, $path, $username, $enabled = true)
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
            // make scan folder in homedir
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . DIR_NAME_SCANS . "\n");
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_SCANS. "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_SCANS. "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_SCANS . "\n");
            // access sys scan user
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\sys_scan_user;s:n;m:grant;p:write;w:dacl\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_SCANS . "\n");
            // make link
            $conn->write("mklink /j " . PATH_SHARE_SCANS . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . DIR_NAME_SCANS . "\n");
        }
        else 
        {
            $conn->write("rmdir " . PATH_SHARE_SCANS . "\\" . $username . " /s /q\n");
        }
//        
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
