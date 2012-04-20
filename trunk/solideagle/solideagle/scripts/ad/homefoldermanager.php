<?php
namespace solideagle\scripts\ad;

use solideagle\logging\Logger;

use solideagle\plugins\ad\SSHManager;

use solideagle\data_access\Type;

use solideagle\data_access\person;
use solideagle\plugins\ad\ManageUser;
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
			$conn = SSHManager::singleton()->getConnection($config["server"]);
			
			if(!HomeFolder::createHomeFolder($conn, $config["server"], $config["homefolderpath"], $config["username"]))
			{
				Logger::log("Creating homefolder failed! " . $key ,PEAR_LOG_ERR);
			}
			if(!ScanFolder::setScanFolder($conn, $config["server"], $config["homefolderpath"], $config["scansharepath"], $config["username"]))
			{
				
				Logger::log("Setting scanfolder failed! " . $key ,PEAR_LOG_ERR);
			}
			if(!WwwFolder::setWwwFolder($conn ,$config["server"], $config["homefolderpath"],$config["wwwsharepath"], $config["username"]))
			{
				Logger::log("Setting www folder failed! " . $key ,PEAR_LOG_ERR);
			}
				
			if(isset($config["uploadsharepath"]))
				UploadFolder::setUploadFolder($conn,$config["server"], $config["homefolderpath"], $config["uploadsharepath"], $config["username"]);
				
			if(isset($config["downloadsharepath"]))
				DownloadFolder::setDownloadFolder($conn,$config["server"], $config["homefolderpath"], $config["downloadsharepath"], $config["username"]);
				
			$conn->exitShell();
		
			//debug!
			Logger::log("SSH SESSION:\n" . $conn->read(),PEAR_LOG_DEBUG);
			
			

			$ret = ManageUser::setHomeFolder($config["username"], "\\\\" . $config["server"]);
			if($ret->isSucces())
			{
				return true;
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
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

	public static function prepareAddHomefolder($server, $homefolderpath, $scansharepath, $wwwsharepath, $user, $uploadsharepath=NULL,$downloadsharepath=NULL)
	{
		$config["action"] = self::ActionAddHomefolder;
		$config["server"] = $server;
		$config["homefolderpath"] = $homefolderpath;
		$config["scansharepath"] = $scansharepath;
		$config["wwwsharepath"] = $wwwsharepath;
		$config["username"] = $user->getAccountUsername();


		if ($user->isTypeOf(Type::TYPE_LEERLING))
			$config["homefolderpath"] .= "\\" . substr($user->getMadeOn(), 2, 2);

		if((!$user->isTypeOf(Type::TYPE_LEERLING)) && $uploadsharepath!=NULL && $downloadsharepath!=NULL)
		{
			$config["uploadsharepath"] = $uploadsharepath;
			$config["downloadsharepath"] = $downloadsharepath;
		}
		
		TaskQueue::insertNewTask($config, $user->getId(), TaskQueue::TypePerson);
	}



	public function getParams()
	{

	}


}
