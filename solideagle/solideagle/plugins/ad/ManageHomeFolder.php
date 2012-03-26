<?php

namespace AD;

require_once 'plugins/ad/HomeFolder.php';
require_once 'plugins/ad/ScanFolder.php';
require_once 'plugins/ad/WwwFolder.php';
require_once 'plugins/ad/DownloadFolder.php';
require_once 'plugins/ad/UploadFolder.php';
//
//use AD\HomeFolder;
//use AD\ScanFolder;
//use AD\WwwFolder;
//use AD\DownloadFolder;
//use AD\UploadFolder;

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
        HomeFolder::createHomeFolder($this->server, $this->homeFolderPath, $this->username);
        ScanFolder::setScanFolder($this->server, $this->homeFolderPath, DIR_NAME_SCANS, $this->username);
        WwwFolder::setWwwFolder($this->server, $this->homeFolderPath, DIR_NAME_WWW, $this->username);
        DownloadFolder::setDownloadFolder($this->server, $this->homeFolderPath, DIR_NAME_DOWNLOADS, $this->username);
        UploadFolder::setUploadFolder($this->server, $this->homeFolderPath, DIR_NAME_UPLOADS, $this->username);
    }
    
}

?>
