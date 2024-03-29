<?php

namespace solideagle\plugins\ad;

use solideagle\logging\Logger;
use solideagle\Config;
use solideagle\plugins\StatusReport;

class HomeFolder
{
    
    /**
     * Creates homedir with subfolders in given path and given read rights groups, administrators & owner get full rights
     * @param string $server                ex: S1
     * @param string $path                  ex: C:\homefolders\
     * @param string $username              ex: bodsonb
     * @param group[] $arrReadRightsGroups 
     * @return boolean 
     */
    public static function createHomeFolder($conn, $path, $username, $arrReadRightsGroups = null)
    {
        // make folder & subfolders
        $conn->write("mkdir " . $path . "\\" . $username . "\n");
       
        // set permissions to local folder
        $conn->write("setacl -ot file -actn ace -ace \"n:" .Config::singleton()->ad_dns . "\\" . $username . ";s:n;p:change;i:sc,so\" -on " . $path . "\\" . $username . "\n");
        $conn->write("setacl -ot file -actn ace -ace \"n:" . Config::singleton()->ad_dns . "\Domain Admins;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\n");        
        $conn->write("takeown /F " . $path . "\\" . $username . " /A /R /D Y\n");
        $conn->write("takeown /F " . $path . "\\" . $username . "\\*.* /A /R /D Y\n");
        
        // add read groups
        $cmd = "icacls " . $path . "\\" . $username . " /q /grant *S-1-5-32-544:F *S-1-5-18:F " . Config::singleton()->ad_dns . "\\" . $username . ":M ";
        
        if ($arrReadRightsGroups != null)
        {
            foreach($arrReadRightsGroups as $group)
            {
                $cmd .= Config::singleton()->ad_dns . "\\" . $group->getName() . ":R ";
            }
        }
        
        $cmd .= "/inheritance:r /T /C\n";
        
        $conn->write($cmd);  

        // share and set permissions
        $cmd = "net share " . $username . "$=" . $path . "\\" . $username . " /grant:" . Config::singleton()->ad_dns . "\\" . $username . ",change /grant:\"" . Config::singleton()->ad_dns . "\\Domain Admins\",read ";
        if ($arrReadRightsGroups != null)
        {
            foreach($arrReadRightsGroups as $group)
            {
                $cmd .= "/grant:" .Config::singleton()->ad_dns . "\\" . $group->getName() . ",read ";
            }
        }
        $cmd .= "/cache:None\n";
        $conn->write($cmd);  
        
        return true;
    }
    
    public static function copyHomeFolder($conn, $username, $homefolderpath, $oldserver) 
    {
        $conn->write("robocopy /S /E /XO /COPYALL /R:1 /W:1 \\\\" . $oldserver . "\\" . $username . "$ " . $homefolderpath . "\\" . $username . "\n");
        return true;
    }
    
    public static function removeShare($conn, $share)
    {
        $conn->write("net share " . $share . " /DELETE \n");
        return true;
    }
//    
//    public static function removeHomeFolder($server, $path, $username, $scanSharePath, $wwwSharePath, $uploadSharePath, $downloadSharePath)
//    {
//        $conn = sshpreformatter::singleton()->getFileForServer($server);
//        if ($conn == null)
//            return false;
//        
//        $conn->write("cmd\n");
//        
//        // remove junctions
//        $conn->write("rmdir " . $scanSharePath . "\\" . $username . " /s /q\n");
//        $conn->write("rmdir " . $wwwSharePath . "\\" . $username . " /s /q\n");
//        $conn->write("rmdir " . $uploadSharePath . "\\" . $username . " /s /q\n");
//        $conn->write("rmdir " . $downloadSharePath . "\\" . $username . " /s /q\n");
//        
//        // unshare homefolder
//        $conn->write("net share /DELETE /y " . $username . "$ \n");
//        
//        $conn->write("echo SHARE_DELETED\n");
//        $conn->read('SHARE_DELETED');
//        
//        // delete folder and subfolders
//        $conn->write("rd /s /q " . $path . "\\" . $username . "\n");
//      
//        $conn->write("exit\nexit\n");
//        $conn->write("echo ENDOFCODE");
//        $conn->read('ENDOFCODE');
//        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
//        $conn->disconnect();
//        
//        return true;
//    }
}

?>
