<?php
namespace adplugin;

require_once 'data_access/TaskInserter.php';

require_once 'plugins/ad/ManageUser.php';
require_once 'plugins/ad/ManageHomeFolder.php';

use AD\ManageUser;
use AD\ManageHomeFolder;
use DataAccess\TaskInserter;


class usermanager implements \DataAccess\TaskInterface
{
	const ActionAddUser = 0;
        const ActionUpdateUser = 1;
        const ActionAddHomeFolder = 2;
        
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
		
		if($config["action"] == self::ActionAddUser || $config["action"] == self::ActionUpdateUser)
		{
                        if (!isset($config["userInfo"]) || !isset($config["arrParentsGroups"]))
                        {
                                $taskqueue->setErrorMessages("Probleem met configuratie");
                                return false;
                        }
                        
                        if($config["action"] == self::ActionAddUser)
                        {
                            $ret = ManageUser::addUser($config["userInfo"],$config["arrParentsGroups"]);
                        }
                        else if ($config["action"] == self::ActionUpdateUser)
                        {
                            
                            $ret = ManageUser::updateUser($config["userInfo"],$config["arrParentsGroups"]);
                        }
			
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
        
        public  function prepareUpdateUser($userInfo,$arrParentsGroups)
	{
		$config["action"] = self::ActionUpdateUser;
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
