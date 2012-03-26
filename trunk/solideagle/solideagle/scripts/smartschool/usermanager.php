<?php
namespace smartschoolplugin;

require_once 'data_access/BaseTask.php';
require_once 'data_access/TaskQueue.php';
require_once 'plugins/smartschool/data_access/SSUser.php';

use DataAccess\BaseTask;
use DataAccess\TaskQueue;

use Smartschool\SSUser;

class usermanager implements TaskInterface
{
	
        const ActionAddSsUser = 0;
    
	public function __construct($taskid = NULL,$personid= NULL)
	{
		parent::__construct($taskid, $personid, parent::TypePerson);
	}
	
	public function getParams()
	{
		
	}
	
	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();
		
		if(!isset($config["action"]))
		{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false;
		}
		
		if($config["action"] == self::ActionAddSsUser)
		{
                        if (!isset($config["user"]))
                        {
                                $taskqueue->setErrorMessages("Probleem met configuratie");
                                return false;
                        }
                        
                        $ret = SSUser::saveUser($config["user"]);
			
			if($ret == "SUCCESS")
			{
				return true;	
			}
                        else{
				$taskqueue->setErrorMessages($ret);
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
	
	public function prepareAddSsUser($user)
	{
		$config["action"] = self::ActionAddSsUser;
                $config["user"] = $user;
		
		$this->addToQueue($config);
	}
	
	
	
}
