<?php

namespace solideagle\plugins\ad;

use solideagle\logging\Logger;
use solideagle\Config;

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
    public static function createHomeFolder($server, $path, $username, $arrReadRightsGroups = null)
    {
    	$conn = SSHManager::singleton()->getConnection($server);
        if ($conn == null)
            return false;

        // make folder & subfolders
        $conn->write("mkdir " . $path . "\\" . $username . "\n");
        //if ($www) $conn->write("mkdir " . $path . "\\" . $username . "\\" . "_www\n");
        //$conn->write("mkdir " . $path . "\\" . $username . "\\" . "_scans\n");
        
        // set permissions to local folder
        //$conn->write("icacls " . $path . "\\" . $username . " /q /reset /t\n");
        $conn->write("setacl -ot file -actn ace -ace \"n:" . Config::singleton()->ad_dns . "\Domain Administrators;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\n");        
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
        
        $conn->write("echo ENDOFCODE\n");
        $conn->read("ENDOFCODE");
//        
//        while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL))
//{
//	echo $data;
//}
        
        return true;
    }
    
    public static function moveHomeFolder($oldServer, $oldPath, $newServer, $newPath, $username, $arrReadRightsGroups) 
    {
        $conn = SSHManager::singleton()->getConnection($server);
        if ($conn == null)
            return false;
        
        if (HomeFolder::createHomeFolder($newServer, $newPath, $username, $arrReadRightsGroups))
        {
        
            $conn->write("cmd\n");

            $conn->write("robocopy /e \\\\" . $oldServer . "\\" . $username . "$ " . $newPath . "\\" . $username . "\n");

            $conn->write("echo COPY_DONE\n");

            $conn->read("COPY_DONE");
            if (HomeFolder::removeHomeFolder($oldServer, $oldPath, $username))
            {
                // share and set permissions
                $cmd = "net share " . $username . "$=" . $newPath . "\\" . $username . " /grant:" .Config::singleton()->ad_dns . "\\" . $username . ",change /grant:\"" .Config::singleton()->ad_dns . "\\Domain Admins\",read ";
                foreach($arrReadRightsGroups as $group)
                {
                    $cmd .= "/grant:" .Config::singleton()->ad_dns . "\\" . $group->getName() . ",read ";
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
    
    public static function removeHomeFolder($server, $path, $username, $scanSharePath, $wwwSharePath, $uploadSharePath, $downloadSharePath)
    {
        $conn = SSHManager::singleton()->getConnection($server);
        if ($conn == null)
            return false;
        
        $conn->write("cmd\n");
        
        // remove junctions
        $conn->write("rmdir " . $scanSharePath . "\\" . $username . " /s /q\n");
        $conn->write("rmdir " . $wwwSharePath . "\\" . $username . " /s /q\n");
        $conn->write("rmdir " . $uploadSharePath . "\\" . $username . " /s /q\n");
        $conn->write("rmdir " . $downloadSharePath . "\\" . $username . " /s /q\n");
        
        // unshare homefolder
        $conn->write("net share /DELETE /y " . $username . "$ \n");
        
        $conn->write("echo SHARE_DELETED\n");
        $conn->read('SHARE_DELETED');
        
        // delete folder and subfolders
        $conn->write("rd /s /q " . $path . "\\" . $username . "\n");
      
        $conn->write("exit\nexit\n");
        $conn->write("echo ENDOFCODE");
        $conn->read('ENDOFCODE');
        $conn->_close_channel(NET_SSH2_CHANNEL_SHELL); 
        $conn->disconnect();
        
        return true;
    }
}

?>
