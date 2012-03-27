<?php

namespace solideagle\plugins\ad;

use Logging\Logger;
use solideagle\Config;

require_once('Net/SSH2.php');

class UploadFolder
{
    
    public static function setUploadFolder($server, $path, $uploadSharePath, $username, $enabled = true)
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
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . Config::$dir_name_uploads . "\n");
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_uploads. "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_uploads. "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_uploads . "\n");
            // people can write once
            $conn->write("setacl -ot file -actn ace -ace \"n:Authenticated Users;s:n;p:FILE_LIST_DIRECTORY,FILE_ADD_FILE,FILE_ADD_SUBDIRECTORY;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_uploads . "\n");

            // make link
            $conn->write("mklink /j " . $uploadSharePath . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . Config::$dir_name_uploads . "\n");
            // set permissions on link!
            $conn->write("setacl -ot file -actn ace -ace \"n:Authenticated Users;s:n;p:FILE_LIST_DIRECTORY,FILE_ADD_FILE,FILE_ADD_SUBDIRECTORY;i:sc,so\" -on " . Config::$path_share_uploads . "\\" . $username . "\n");
        }
        else
        {
            $conn->write("rmdir " . $uploadSharePath . "\\" . $username . " /s /q\n");
        }
        
        //while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL))
        
        $conn->write("exit\nexit\n");
        $conn->write("echo ENDOFCODE");
        $conn->read('ENDOFCODE');
        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
        $conn->disconnect();
    }
    
}

?>
