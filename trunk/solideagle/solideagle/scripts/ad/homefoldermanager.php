<?php
namespace solideagle\scripts\ad;

use solideagle\plugins\ad\homefolderPlugin;

use solideagle\plugins\ad\sshpreformatter;

use solideagle\logging\Logger;

use solideagle\data_access\Type;

use solideagle\data_access\person;

use solideagle\data_access\PlatformAD;

use solideagle\data_access\TaskQueue;

use solideagle\data_access\TaskInterface;

use solideagle\plugins\ad\ManageUser;

use solideagle\plugins\ad\HomeFolder;

use solideagle\plugins\ad\ScanFolder;

use solideagle\plugins\ad\WwwFolder;

use solideagle\plugins\ad\DownloadFolder;

use solideagle\plugins\ad\UploadFolder;


class homefoldermanager implements TaskInterface
{
	const ActionAddHomefolder = "AddHomefolder";
	
	const ActionCopyHomefolder = "CopyHomefolder";
	const ActionRemoveShare = "RemoveShare";

	public function runTask($taskqueue)
	{
		$config = $taskqueue->getConfiguration();

		if($config["action"] == self::ActionAddHomefolder)
		{
			$homefolderPlugin = new homefolderPlugin($config["server"]);

			$homefolderPlugin->createHomeFolder($config["person"],$config["paths"]->homefolderpath,$config["paths"]->wwwsharepath,$config["paths"]->scansharepath);
				
			if($config["person"]->isTypeOf(Type::TYPE_LEERKRACHT))
			{
				$homefolderPlugin->addUpDownToHomeFolder($config["paths"]->downloadsharepath,$config["paths"]->uploadsharepath);
			}
			
			$ret = ManageUser::setHomeFolder($config["person"]->getAccountUsername(), "\\\\" . $config["server"]);
				
			if($ret->isSucces())
			{
				$platformad = PlatformAD::getPlatformConfigByPersonId($config["person"]->getId());
				if ($platformad == null)
				{
					$taskqueue->setErrorMessages("Cannot set homefolder in AD because the account does not exist");
					return false;
				}
				$homedir = "\\\\" . $config["server"] . "\\" . $config["person"]->getAccountUsername() . "$";

				$platformad->setHomedir($homedir);

				PlatformAD::updatePlatform($platformad);
				return true;
			}else{
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}
		}
		else if($config["action"] == self::ActionCopyHomefolder && isset($config["server"]) && isset($config["homefolderpath"]) && isset($config["person"]) && isset($config["oldserver"]) && isset($config["oldshare"]))
		{
			$username = $config["person"]->getAccountUsername();

			$conn = sshpreformatter::singleton()->getFileForServer($config["server"]);

			$ret = HomeFolder::copyHomeFolder($conn, $username, $config["homefolderpath"], $config["oldserver"]);

			if($ret)
			{
				$conn = sshpreformatter::singleton()->getFileForServer($config["oldserver"]);
				$ret = HomeFolder::removeShare($conn, $config["oldshare"]);
				return true;
			}if(!$ret)
			{
				Logger::log($ret->getError(),PEAR_LOG_ERR);
				$taskqueue->setErrorMessages($ret->getError());
				return false;
			}

		}
		
		$taskqueue->setErrorMessages("Probleem met configuratie");
		return false;
	}

	/**
	 *
	 * @param unknown_type $server
	 * @param unknown_type $homefolderpath
	 * @param unknown_type $scansharepath
	 * @param unknown_type $wwwsharepath
	 * @param Person $user
	 * @param unknown_type $uploadsharepath
	 * @param unknown_type $downloadsharepath
	 */
	public static function prepareAddHomefolder($server, $homefolderpath, $scansharepath, $wwwsharepath, $user, $uploadsharepath=NULL,$downloadsharepath=NULL)
	{
		$config["action"] = self::ActionAddHomefolder;
		$config["server"] = $server;

		$paths = new \stdClass();

		$paths->homefolderpath = $homefolderpath;
		$paths->wwwsharepath = $wwwsharepath;
		$paths->scansharepath = $scansharepath;
		$paths->uploadsharepath = $uploadsharepath;
		$paths->downloadsharepath = $downloadsharepath;

		$config["paths"] = $paths;
		$config["person"] = $user;

		TaskQueue::insertNewTask($config, $user->getId(), TaskQueue::TypePerson);
	}

	public static function prepareCopyHomefolder($newserver, $newhomefolderpath, $person, $newscansharepath,
			$newwwwsharepath, $newuploadsharepath, $newdownloadsharepath)
	{
		// prepare add homefolder task
		self::prepareAddHomefolder($newserver, $newhomefolderpath, $newscansharepath, $newwwwsharepath, $person, $newuploadsharepath, $newdownloadsharepath);

		// copy homefolder task
		$config["action"] = self::ActionCopyHomefolder;
		$config["server"] = $newserver;
		$config["homefolderpath"] = self::makeHomefolderPath($newhomefolderpath, $person);
		$config["person"] = $person;

		$oldsharepath = PlatformAD::getPlatformConfigByPersonId($person->getId())->getHomedir();
		if ($oldsharepath != null)
		{
			if ($oldsharepath[0] == "\\" && $oldsharepath[1] == "\\")
			{
				$serversharepath = substr($oldsharepath, 2);

				for ($i = 0; $i < strlen($serversharepath); $i++)
				{
					if ($serversharepath[$i] == "/" || $serversharepath[$i] == "\\")
					{
						$server = substr($serversharepath, 0, $i);
						$config["oldserver"] = $server;
						$config["oldshare"] = substr($serversharepath, $i + 1);

						break;
					}
				}
			}
		}

		TaskQueue::insertNewTask($config, $person->getId(), TaskQueue::TypePerson);

		// delete old share

	}


}
