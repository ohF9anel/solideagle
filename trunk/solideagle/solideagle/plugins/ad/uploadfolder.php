<?php

namespace solideagle\plugins\ad;

use solideagle\logging\Logger;
use solideagle\Config;



class UploadFolder
{
    
    public static function setUploadFolder($conn, $path, $uploadSharePath, $username, $enabled = true)
    {
    
        
        if ($enabled)
        {
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_uploads . "\n");
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_uploads. "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_uploads. "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_uploads . "\n");
            // people can write once
            $conn->write("setacl -ot file -actn ace -ace \"n:Authenticated Users;s:n;p:FILE_LIST_DIRECTORY,FILE_ADD_FILE,FILE_ADD_SUBDIRECTORY;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_uploads . "\n");

            // make link
            $conn->write("mklink /j " . $uploadSharePath . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_uploads . "\n");
            // set permissions on link!
            $conn->write("setacl -ot file -actn ace -ace \"n:Authenticated Users;s:n;p:FILE_LIST_DIRECTORY,FILE_ADD_FILE,FILE_ADD_SUBDIRECTORY;i:sc,so\" -on " . Config::singleton()->path_share_uploads . "\\" . $username . "\n");
        }
        else
        {
            $conn->write("rmdir " . $uploadSharePath . "\\" . $username . " /s /q\n");
        }
        
//        $conn->write("echo ENDOFCODE\n");
//        $conn->read("ENDOFCODE");
        
//        while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL))
//            echo $data;
     
        
    }
    
}

?>
