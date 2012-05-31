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
			$username = $config["person"]->getAccountUsername();
			
			$yearfolder = self::getStudentYear($config["person"]);
			
			$typefolder = self::getHomefolderPath($config["person"]);
			
			$homefolderPlugin = new homefolderPlugin($config["server"]);
			
			$fullpath = $config["paths"]->homefolderpath . $typefolder . $yearfolder . "\\" . $username;
			$wwwJunctionPath = $config["paths"]->wwwsharepath . $yearfolder;
			$scanJunctionPath = $config["paths"]->scansharepath . "\\" . $username;
			
			Logger::log("Creating homefolder on " . $config["server"] . " path: " . $fullpath ." for user: " . $username,PEAR_LOG_INFO);
			
			$homefolderPlugin->createHomeFolder($username,$fullpath,$wwwJunctionPath,$scanJunctionPath);
			
			if($config["person"]->isTypeOf(Type::TYPE_LEERKRACHT))
			{
				Logger::log("Adding up and downfolder on " . $config["server"] . " path: " . $fullpath ." for user: " . $username,PEAR_LOG_INFO);
				
				$homefolderPlugin->addUpDownToHomeFolder($username,$fullpath);
			}
			
			$ret = ManageUser::setHomeFolder($username, "\\\\" . $config["server"]);
			
			if($ret->isSucces())
			{
				$platformad = PlatformAD::getPlatformConfigByPersonId($config["person"]->getId());
				if ($platformad == null)
				{
					$taskqueue->setErrorMessages("Cannot set homefolder in AD because the account does not exist");
					return false;
				}
				$homedir = "\\\\" . $config["server"] . "\\" . $username . "$";

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
		else{
			$taskqueue->setErrorMessages("Probleem met configuratie");
			return false; //it failed for some reason
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

	private static function getStudentYear($person)
	{
		if(is_numeric(substr($person->getAccountUsername(), -3)))
		{
			return "\\" . substr($person->getAccountUsername(), -3, 2);
		}else if(is_numeric(substr($person->getAccountUsername(), -2))){
			return "\\" . substr($person->getAccountUsername(), -2);
		}else{
			return "";
		}
	}

	private static function getHomefolderPath($person)
	{
		if ($person->isTypeOf(Type::TYPE_LEERLING))
		{
			return "\\leerlingen";
		}
		else if($person->isTypeOf(Type::TYPE_LEERKRACHT))
		{
			return "\\leerkrachten";
		}
		else if($person->isTypeOf(Type::TYPE_STAFF))
		{
			return "\\staff";
		}else{
			return "\\other";
		}
	}
}
