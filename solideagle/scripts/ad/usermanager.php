<?php
namespace adscripts;

require_once 'data_access/BaseTask.php';
require_once 'data_access/TaskQueue.php';
require_once 'plugins/ad/ManageUser.php';
require_once 'plugins/ad/ManageHomeFolder.php';

use AD\ManageUser;
use AD\ManageHomeFolder;
use DataAccess\BaseTask;
use DataAccess\TaskQueue;

class usermanager extends \DataAccess\BaseTask
{
	const ActionAddUser = 0;
        const ActionAddHomeFolder = 1;
        
        public function __construct($taskid = null, $personid = null)
	{
		parent::__construct($taskid, $personid, parent::TypePerson);
	}
        
        public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();
		
		if(!isset($config["action"]))
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}
		
		if($config["action"] == self::ActionAddUser)
		{
                        if (!isset($config["userInfo"]) || !isset($config["arrParentsGroups"]))
                        {
                                $taskqueue->setErrorMessages("Probleem met configuratie");
                                return false;
                        }
                        
			$ret = ManageUser::addUser($config["userInfo"],$config["arrParentsGroups"]);
			
			if($ret[0] === true)
			{
				return true;	
			}
                        else{
				$taskqueue->setErrorMessages($ret[1]);
				return false;
			}
                }
                else if($config["action"] == self::ActionAddHomeFolder)
                {
                        if (!isset($config["server"]) || !isset($config["username"]) || !isset($config["homeFolderPath"]) || !isset($config["scanSharePath"]) || !isset($config["downloadSharePath"]) || !isset($config["uploadSharePath"]) || !isset($config["wwwSharePath"]))
                        {
                                $taskqueue->setErrorMessages("Probleem met configuratie");
                                return false;
                        }
                        $mhf = new ManageHomeFolder($config["server"], $config["username"], $config["homeFolderPath"], $config["scanSharePath"], $config["wwwSharePath"], $config["downloadSharePath"], $config["uploadSharePath"]);
                        $mhf->startHomeFolderManager();
                }
                else
                {
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
		}
		
		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}
	
	public function createTaskFromParams($params)
	{
	
	}
	
	public  function prepareAddUser($userInfo,$arrParentsGroups)
	{
		$config["action"] = self::ActionAddUser;
                $config["userInfo"] = $userInfo;
		$config["arrParentsGroups"] = $arrParentsGroups;
		
		$this->addToQueue($config);
	}
        
        public  function prepareAddHomeFolder($server, $username, $homeFolderPath, $scanSharePath, $wwwSharePath, $downloadSharePath, $uploadSharePath)
	{
		$config["action"] = self::ActionAddHomeFolder;
                $config["server"] = $server;
		$config["username"] = $username;
                $config["homeFolderPath"] = $homeFolderPath;
                $config["scanSharePath"] = $scanSharePath;
                $config["wwwSharePath"] = $wwwSharePath;
                $config["downloadSharePath"] = $downloadSharePath;
                $config["uploadSharePath"] = $uploadSharePath;
		
		$this->addToQueue($config);
	}	
	
	public function getParams()
	{
	
	}
        
}

?>
