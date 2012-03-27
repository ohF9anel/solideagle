<?php

namespace solideagle\plugins\ad;

use Logging\Logger;
use solideagle\Config;



class WwwFolder
{
    
    public static function setWwwFolder($server, $path, $wwwSharePath, $username, $enabled = true)
    {
        $conn = SSHManager::singleton()->getConnection($server);
        
      
        
        if ($enabled)
        {
            // make www folder in homedir
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . Config::$dir_name_www . "\n");
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_www. "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_www. "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_www . "\n");
            // access webserver with user sysweb
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::$ad_netbios . "\\sysweb;s:n;m:grant;p:read;w:dacl\" -on " . $path . "\\" . $username . "\\" . Config::$dir_name_www . "\n");
            // make link
            $conn->write("mklink /j " . $wwwSharePath . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . Config::$dir_name_www . "\n");
        }
        else 
        {
            $conn->write("rmdir " . $wwwSharePath . "\\" . $username . " /s /q\n");
        }
        
        //while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL)) echo $data;
        
     
  
        
    }
    
}

?>
