<?php

namespace solideagle\plugins\ad;

use solideagle\plugins\ad\HomeFolder;
use solideagle\plugins\ad\ScanFolder;
use solideagle\plugins\ad\WwwFolder;
use solideagle\plugins\ad\DownloadFolder;
use solideagle\plugins\ad\UploadFolder;
use solideagle\Config;

class ManageHomeFolder
{
    
    public $server;
    public $homeFolderPath;
    public $scanSharePath;
    public $downloadSharePath;
    public $uploadSharePath;
    public $wwwSharePath;
    
    public function __construct($server, $username, $homeFolderPath, $scanSharePath, $wwwSharePath, $downloadSharePath, $uploadSharePath)
    {
        $this->server = $server;
        $this->username = $username;
        $this->homeFolderPath = $homeFolderPath;
        $this->scanSharePath = $scanSharePath;
        $this->downloadSharePath = $downloadSharePath;
        $this->uploadSharePath = $uploadSharePath;
        $this->wwwSharePath = $wwwSharePath;
    }

    public function startHomeFolderManager()
    {
    	
        SSHManager::singleton()->getConnection($this->server)->write("cmd\n");
    	
        HomeFolder::createHomeFolder($this->server, $this->homeFolderPath, $this->username);
        ScanFolder::setScanFolder($this->server, $this->homeFolderPath, $this->scanSharePath, $this->username);
        WwwFolder::setWwwFolder($this->server, $this->homeFolderPath, $this->wwwSharePath, $this->username);
        DownloadFolder::setDownloadFolder($this->server, $this->homeFolderPath, $this->downloadSharePath, $this->username);
        UploadFolder::setUploadFolder($this->server, $this->homeFolderPath, $this->uploadSharePath, $this->username);
        
        SSHManager::singleton()->getConnection($this->server)->write("exit\nexit\n");
       
    }
    
}

?>
