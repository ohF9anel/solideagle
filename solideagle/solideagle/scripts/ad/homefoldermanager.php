<?php
namespace solideagle\scripts\ad;

use solideagle\data_access\person;
use solideagle\plugins\ad\HomeFolder;
use solideagle\plugins\ad\ScanFolder;
use solideagle\plugins\ad\WwwFolder;
use solideagle\plugins\ad\DownloadFolder;
use solideagle\plugins\ad\UploadFolder;
use solideagle\data_access\TaskQueue;
use solideagle\data_access\TaskInterface;


class homefoldermanager implements TaskInterface
{
	const ActionAddHomefolder = 0;
	const ActionAddUploadFolder = 1;
        const ActionAddDownloadFolder = 2;

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();
		
		if(!isset($config["action"]))
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}
		else if($config["action"] == self::ActionAddHomefolder && isset($config["server"]) && isset($config["homefolderpath"]) && isset($config["scansharepath"]) && isset($config["wwwsharepath"]) && isset($config["username"]))
		{
                        SSHManager::singleton()->getConnection($this->server)->write("cmd\n");
			HomeFolder::createHomeFolder($this->server, $this->homeFolderPath, $this->username);
                        ScanFolder::setScanFolder($this->server, $this->homeFolderPath, $this->scanSharePath, $this->username);
                        WwwFolder::setWwwFolder($this->server, $this->homeFolderPath, $this->wwwSharePath, $this->username);
                        SSHManager::singleton()->getConnection($this->server)->write("exit\nexit\n");
                        return true;
		}
                else if($config["action"] == self::ActionAddUploadFolder && isset($config["server"]) && isset($config["homefolderpath"]) && isset($config["uploadsharepath"]) && isset($config["username"]))
		{
                        SSHManager::singleton()->getConnection($this->server)->write("cmd\n");
			UploadFolder::setUploadFolder($this->server, $this->homeFolderPath, $this->uploadSharePath, $this->username);
                        SSHManager::singleton()->getConnection($this->server)->write("exit\nexit\n");
                        return true;
		}
                else if($config["action"] == self::ActionAddDownloadFolder && isset($config["server"]) && isset($config["homefolderpath"]) && isset($config["downloadsharepath"]) && isset($config["username"]))
		{
                        SSHManager::singleton()->getConnection($this->server)->write("cmd\n");
			DownloadFolder::setDownloadFolder($this->server, $this->homeFolderPath, $this->downloadSharePath, $this->username);
                        SSHManager::singleton()->getConnection($this->server)->write("exit\nexit\n");
                        return true;
		}
                else{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
		
		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}
	
	public function createTaskFromParams($params)
	{
	
	}
	
	public static function prepareAddHomefolder($server, $homefolderpath, $scansharepath, $wwwsharepath, $user, $useyearsubfolder = true)
	{
		$config["action"] = self::ActionAddHomefolder;
                $config["server"] = $server;
		$config["homefolderpath"] = $homefolderpath;
                $config["scansharepath"] = $scansharepath;
                $config["wwwsharepath"] = $wwwsharepath;
		$config["username"] = $user->getAccountUsername();
		if ($useyearsubfolder)
                    $config["homefolderpath"] .= "/" . substr($user->getMadeOn(), 2, 2);
		
		TaskQueue::insertNewTask($config, $user->getId(), TaskQueue::TypePerson);
	}
        
        public static function prepareAddUploadFolder($server, $homefolderpath, $uploadsharepath, $user, $useyearsubfolder = false)
	{
		$config["action"] = self::ActionAddUploadFolder;
                $config["server"] = $server;
		$config["homefolderpath"] = $homefolderpath;
                $config["uploadsharepath"] = $uploadsharepath;
		$config["username"] = $user->getAccountUsername();
		if ($useyearsubfolder)
                    $config["homefolderpath"] .= "/" . substr($user->getMadeOn(), 2, 2);
		
		TaskQueue::insertNewTask($config, $user->getId(), TaskQueue::TypePerson);
	}
        
        public static function prepareAddDownloadFolder($server, $homefolderpath, $downloadsharepath, $user, $useyearsubfolder = false)
	{
		$config["action"] = self::ActionAddDownloadFolder;
                $config["server"] = $server;
		$config["homefolderpath"] = $homefolderpath;
                $config["downloadsharepath"] = $downloadsharepath;
		$config["username"] = $user->getAccountUsername();
		if ($useyearsubfolder)
                    $config["homefolderpath"] .= "/" . substr($user->getMadeOn(), 2, 2);
		
		TaskQueue::insertNewTask($config, $user->getId(), TaskQueue::TypePerson);
	}
	
	public function getParams()
	{
                
	}
	
	
}
