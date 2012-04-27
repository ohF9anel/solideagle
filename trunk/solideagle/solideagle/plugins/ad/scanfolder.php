<?php

namespace solideagle\plugins\ad;

use solideagle\logging\Logger;
use solideagle\Config;


class ScanFolder
{
    
    public static function setScanFolder($conn,$server, $path, $scanSharePath, $username, $enabled = true)
    {
   
        
        if ($enabled)
        {
            // make scan folder in homedir
            $conn->write("mkdir " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_scans . "\n");
            // give access to
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_scans. "\n");
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_scans. "\n");
            // protection folder
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\" . $username . ";s:n;m:deny;p:delete;i:np\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_scans . "\n");
            // access sys scan user
            $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\sys_scan_user;s:n;m:grant;p:write;w:dacl\" -on " . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_scans . "\n");
            // make link
            $conn->write("mklink /j " . $scanSharePath . "\\" . $username . ' ' . $path . "\\" . $username . "\\" . Config::singleton()->dir_name_scans . "\n");
        }
        else 
        {
            $conn->write("rmdir " . $scanSharePath . "\\" . $username . " /s /q\n");
        }

        
		return true;
        
        
    }
    
}

?>
