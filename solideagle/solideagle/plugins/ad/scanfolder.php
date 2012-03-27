<?php

namespace solideagle\plugins\ad;

use solideagle\logging\Logger;
use solideagle\Config;

require_once('Net/SSH2.php');

class ScanFolder
{
    
    public static function setScanFolder($server, $path, $scanSharePath, $username, $enabled = true)
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
            // make scan folder in homedir
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . Config::$dir_name_scans . "\n");
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_scans. "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_scans. "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_scans . "\n");
            // access sys scan user
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\sys_scan_user;s:n;m:grant;p:write;w:dacl\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_scans . "\n");
            // make link
            $conn->write("mklink /j " . $scanSharePath . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . Config::$dir_name_scans . "\n");
        }
        else 
        {
            $conn->write("rmdir " . $scanSharePath . "\\" . $username . " /s /q\n");
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
