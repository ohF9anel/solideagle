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

        
        
        // make folder & subfolders
        $conn->write("mkdir " . $path . "\\" . $username . "\n");
        //if ($www) $conn->write("mkdir " . $path . "\\" . $username . "\\" . "_www\n");
        //$conn->write("mkdir " . $path . "\\" . $username . "\\" . "_scans\n");
        
        // set permissions to local folder
        //$conn->write("icacls " . $path . "\\" . $username . " /q /reset /t\n");
        $conn->write("setacl -ot file -actn ace -ace \"n:" . Config::$ad_netbios . "\Domain Administrators;s:n;p:full;i:sc,so\" -on " . $path . "\\" . $username . "\n");        
        $conn->write("takeown /F " . $path . "\\" . $username . " /A /R /D Y\n");
        $conn->write("takeown /F " . $path . "\\" . $username . "\\*.* /A /R /D Y\n");
        
        // add read groups
        $cmd = "icacls " . $path . "\\" . $username . " /q /grant *S-1-5-32-544:F *S-1-5-18:F " . Config::$ad_netbios . "\\" . $username . ":M ";
        
        if ($arrReadRightsGroups != null)
        {
            foreach($arrReadRightsGroups as $group)
            {
                $cmd .= Config::$ad_netbios . "\\" . $group->getName() . ":R ";
            }
        }
        
        $cmd .= "/inheritance:r /T /C\n";
        
        $conn->write($cmd);  

        // share and set permissions
        $cmd = "net share " . $username . "$=" . $path . "\\" . $username . " /grant:" . Config::$ad_netbios . "\\" . $username . ",change /grant:\"" . Config::$ad_netbios . "\\Domain Admins\",read ";
        if ($arrReadRightsGroups != null)
        {
            foreach($arrReadRightsGroups as $group)
            {
                $cmd .= "/grant:" .Config::$ad_netbios . "\\" . $group->getName() . ",read ";
            }
        }
        $cmd .= "/cache:None\n";
        $conn->write($cmd);  
        

        return true;
    }
    
    public static function moveHomeFolder($oldServer, $oldPath, $newServer, $newPath, $username, $arrReadRightsGroups) 
    {
        $conn = new \Net_SSH2($newServer);
        if (!$conn->login(S1_ADMINISTRATOR, Config::$ad_password))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . Config::$ad_dc_host . ".", PEAR_LOG_ERR);
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
                $cmd = "net share " . $username . "$=" . $newPath . "\\" . $username . " /grant:" .Config::$ad_netbios . "\\" . $username . ",change /grant:\"" .Config::$ad_netbios . "\\Domain Admins\",read ";
                foreach($arrReadRightsGroups as $group)
                {
                    $cmd .= "/grant:" .Config::$ad_netbios . "\\" . $group->getName() . ",read ";
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
        if (!$conn->login(S1_ADMINISTRATOR, Config::$ad_password))
        {
            return false;
            Logger::getLogger()->log(__FILE__ . " " . __FUNCTION__ . " on line " . __LINE__ . ": \nLogin to SSH failed on " . Config::$ad_dc_host . ".", PEAR_LOG_ERR);
        }
        
        $conn->write("cmd\n");
        
        // unshare folder
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
