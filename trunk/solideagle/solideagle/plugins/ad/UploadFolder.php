<?php

namespace AD;

require_once('Net/SSH2.php');
require_once 'logging/Logger.php';
require_once 'config.php';
use Logging\Logger;

class UploadFolder
{
    
    public static function setUploadFolder($server, $path, $username, $enabled = true)
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
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . DIR_NAME_UPLOADS . "\n");
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_UPLOADS. "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_UPLOADS. "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" . AD_NETBIOS . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_UPLOADS . "\n");
            // people can write once
            $conn->write("setacl -ot file -actn ace -ace \"n:Authenticated Users;s:n;p:FILE_LIST_DIRECTORY,FILE_ADD_FILE,FILE_ADD_SUBDIRECTORY;i:sc,so\" -on " . $path . "\\" . $username . "\\" . DIR_NAME_UPLOADS . "\n");

            // make link
            $conn->write("mklink /j " . PATH_SHARE_UPLOADS . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . DIR_NAME_UPLOADS . "\n");
            // set permissions on link!
            $conn->write("setacl -ot file -actn ace -ace \"n:Authenticated Users;s:n;p:FILE_LIST_DIRECTORY,FILE_ADD_FILE,FILE_ADD_SUBDIRECTORY;i:sc,so\" -on " . PATH_SHARE_UPLOADS . "\\" . $username . "\n");
        }
        else
        {
            $conn->write("rmdir " . PATH_SHARE_UPLOADS . "\\" . $username . " /s /q\n");
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
