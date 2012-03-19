<?php

namespace AD;

include('ConnectionSsh.php');
require_once 'logging/Logger.php';
require_once 'config.php';
use Logging\Logger;

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
    public static function createHomeFolder($server, $path, $username, $arrReadRightsGroups)
    {
        $conn = new \Net_SSH2($server);
        if (!$conn->login(S1_ADMINISTRATOR, AD_PASSWORD))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . AD_DC_HOST . ".", PEAR_LOG_ERR);
        }
        
        $conn->write("cmd\n");
        
        // make folder & subfolders
        $conn->write("mkdir " . $path . "\\" . $username . "\n");
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "www\n");
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "scans\n");
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "Documents\n");
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "Downloads\n");
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "Pictures\n");
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "Music\n");
        $conn->write("mkdir " . $path . "\\" . $username . "\\" . "Movies\n");
        
        // set permissions to local folder
        $conn->write("icacls " . $path . "\\" . $username . " /q /reset /t\n");
        $conn->write("takeown /F " . $path . "\\" . $username . " /A /R /D Y\n");
        $conn->write("takeown /F " . $path . "\\" . $username . "\\*.* /A /R /D Y\n");
        
        // add read groups
        $cmd = "icacls " . $path . "\\" . $username . " /q /grant *S-1-5-32-544:F *S-1-5-18:F " . AD_NETBIOS . "\\" . $username . ":M ";
        
        foreach($arrReadRightsGroups as $group)
        {
            $cmd .= AD_NETBIOS . "\\" . $group->getName() . ":R ";
        }
        
        $cmd .= "/inheritance:r /T /C\n";
        
        $conn->write($cmd);  

        // share and set permissions
        $cmd = "net share " . $username . "$=" . $path . "\\" . $username . " /grant:" . AD_NETBIOS . "\\" . $username . ",change /grant:\"" . AD_NETBIOS . "\\Domain Admins\",read ";
        foreach($arrReadRightsGroups as $group)
        {
            $cmd .= "/grant:" . AD_NETBIOS . "\\" . $group->getName() . ",read ";
        }
        $cmd .= "/cache:None\n";
        
        $conn->write($cmd);  
        
        $conn->write("exit\nexit\n");
        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
        $conn->disconnect();
        
        return true;
    }
    
    public static function moveHomeFolder($oldServer, $oldPath, $newServer, $newPath, $username, $arrReadRightsGroups) 
    {
        $conn = new \Net_SSH2($newServer);
        if (!$conn->login(S1_ADMINISTRATOR, AD_PASSWORD))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . AD_DC_HOST . ".", PEAR_LOG_ERR);
        }
        
        if (HomeFolder::createHomeFolder($newServer, $newPath, $username, $arrReadRightsGroups))
        {
        
            $conn->write("cmd\n");

            $conn->write("robocopy /e \\\\" . $oldServer . "\\" . $username . "$ " . $newPath . "\\" . $username . "\n");

            $conn->write("echo COPY_DONE\n");

            $conn->read("COPY_DONE");
            if (HomeFolder::removeHomeFolder($oldServer, $oldPath, $username))
            {
                // share and set permissions
                $cmd = "net share " . $username . "$=" . $newPath . "\\" . $username . " /grant:" . AD_NETBIOS . "\\" . $username . ",change /grant:\"" . AD_NETBIOS . "\\Domain Admins\",read ";
                foreach($arrReadRightsGroups as $group)
                {
                    $cmd .= "/grant:" . AD_NETBIOS . "\\" . $group->getName() . ",read ";
                }
                $cmd .= "/cache:None\n";

                $conn->write($cmd);  

                $conn->write("exit\nexit\n");
                $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
                $conn->disconnect();
                
                return true;
            }
        }
    }
    
    public static function removeHomeFolder($server, $path, $username)
    {
        $conn = new \Net_SSH2($server);
        if (!$conn->login(S1_ADMINISTRATOR, AD_PASSWORD))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . AD_DC_HOST . ".", PEAR_LOG_ERR);
        }
        
        $conn->write("cmd\n");
        
        // unshare folder
        $conn->write("net share /DELETE /y " . $username . "$ \n");
        
        $conn->write("echo SHARE_DELETED\n");
        $conn->read('SHARE_DELETED');
        
        // delete folder and subfolders
        $conn->write("rd /s /q " . $path . "\\" . $username . "\n");
      
        $conn->write("exit\nexit\n");
        
        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
        $conn->disconnect();
        
        return true;
    }
}

?>
