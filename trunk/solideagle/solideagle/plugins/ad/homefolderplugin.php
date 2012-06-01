<?php
namespace solideagle\plugins\ad;

use solideagle\data_access\Type;

use solideagle\Config;

class homefolderPlugin
{

	private $conn;
	private $homedirpath;
	private $username;

	public function __construct($server)
	{
		$this->conn = sshpreformatter::singleton()->getFileForServer($server);
	}

	public function createHomeFolder($person,$pathIn,$wwwJunctionPath,$scanJunctionPath)
	{
		$username = $person->getAccountUsername();
		$this->username = $username;

		$yearfolder = "";

		if($person->isTypeOf(Type::TYPE_LEERLING))
		{
			$yearfolder = self::getStudentYear($person);
		}

		$typefolder = self::getHomefolderPath($person);

		$this->homedirpath = $pathIn . $typefolder . $yearfolder . "\\" . $username;



		$this->conn->write("mkdir " . $this->homedirpath); //create path for user
			
		$homedirwwwpath = $this->homedirpath . "\\" . Config::singleton()->dir_name_www;
		$homedirscanpath = $this->homedirpath .  "\\" . Config::singleton()->dir_name_scans;
			
		$this->conn->write("mkdir " . $homedirwwwpath); //create www path
		$this->conn->write("mkdir " . $homedirscanpath); //create scan path
			
		if($person->isTypeOf(Type::TYPE_LEERLING))
		{
			$this->conn->write("setacl -ot file ^
					-actn clear -clr dacl,sacl ^
					-actn rstchldrn -rst dacl,sacl ^
					-actn ace -ace \"n:dbz.lok\\".$username.";s:n;p:change;i:sc,so\" ^
					-actn ace -ace \"n:dbz.lok\\admins;s:n;p:full;i:sc,so\" ^
					-actn ace -ace \"n:dbz.lok\\Domain Admins;s:n;p:full;i:sc,so\" ^
					-actn ace -ace \"n:dbz.lok\\studentfolders_read;s:n;p:read;i:sc,so\" ^
					-actn setprot -op \"dacl:p_nc;sacl:p_nc\" ^
					-on \"". $this->homedirpath ."\"");
		}else{

			$this->conn->write("setacl -ot file ^
					-actn clear -clr dacl,sacl ^
					-actn rstchldrn -rst dacl,sacl ^
					-actn ace -ace \"n:dbz.lok\\".$username.";s:n;p:change;i:sc,so\" ^
					-actn ace -ace \"n:dbz.lok\\admins;s:n;p:full;i:sc,so\" ^
					-actn ace -ace \"n:dbz.lok\\Domain Admins;s:n;p:full;i:sc,so\" ^
					-actn setprot -op \"dacl:p_nc;sacl:p_nc\" ^
					-on \"". $this->homedirpath ."\"");
		}


			
		$this->conn->write("setacl -ot file ^
				-actn ace -ace \"n:dbz.lok\\".$username.";s:n;p:delete;i:np;m:deny\" ^
				-actn ace -ace \"n:dbz.lok\\sysweb;s:n;p:read;i:sc,so;m:grant\" ^
				-on \"". $homedirwwwpath ."\"");
			
		$this->conn->write("setacl -ot file ^
				-actn ace -ace \"n:dbz.lok\\".$username.";s:n;p:delete;i:np;m:deny\" ^
				-actn ace -ace \"n:dbz.lok\\sys_scan_user;s:n;p:read;i:sc,so;m:grant\" ^
				-on \"". $homedirscanpath ."\"");

		$this->conn->write("net share ".$username."$=\"". $this->homedirpath ."\" /GRANT:".$username.",change");

		if($person->isTypeOf(Type::TYPE_LEERLING)) //need to create the year folder for students
		{
			$wwwJunctionPath = $wwwJunctionPath . $yearfolder;
			$this->conn->write("mkdir " . $wwwJunctionPath); //create junction path
		}

		$wwwJunctionPath = $wwwJunctionPath . "\\" . $username;
		$scanJunctionPath = $scanJunctionPath . "\\" . $username;
		
		

		$this->conn->write("mklink /j \"" . $wwwJunctionPath .  "\" \"" . $homedirwwwpath . "\"");
		$this->conn->write("mklink /j \"" . $scanJunctionPath . "\" \"" . $homedirscanpath . "\"");

	}

	public function addUpDownToHomeFolder($downloadJunctionPath,$uploadJunctionPath)
	{
		$homedirdownloadpath = $this->homedirpath . "\\" . Config::singleton()->dir_name_downloads;
		$homediruploadpath = $this->homedirpath .  "\\" . Config::singleton()->dir_name_uploads;

		$this->conn->write("mkdir " . $homedirdownloadpath);
		$this->conn->write("mkdir " . $homediruploadpath);

		$downloadJunctionPath = $downloadJunctionPath . "\\" . $this->username;
		$uploadJunctionPath = $uploadJunctionPath . "\\" . $this->username;
		
		$this->conn->write("setacl -ot file ^
				-actn ace -ace \"n:dbz.lok\\".$this->username.";s:n;p:delete;i:np;m:deny\" ^
				-actn ace -ace \"n:S-1-5-11;s:y;p:read;i:sc,so;m:grant\" ^
				-on \"". $homedirdownloadpath ."\"");
		
		$this->conn->write("setacl -ot file ^
				-actn ace -ace \"n:dbz.lok\\".$this->username.";s:n;p:delete;i:np;m:deny\" ^
				-actn ace -ace \"n:S-1-5-11;s:y;p:FILE_LIST_DIRECTORY,FILE_ADD_FILE,FILE_WRITE_EA,FILE_WRITE_ATTRIBUTES;i:sc,so;m:grant\" ^
				-on \"". $homediruploadpath ."\"");

		$this->conn->write("mklink /j \"" . $downloadJunctionPath. "\" \"" . $homedirdownloadpath . "\"");
		$this->conn->write("mklink /j \"" . $uploadJunctionPath . "\" \"" . $homediruploadpath . "\"");

	}

	/*
	 net share leerlingen$="E:\homefolders\leerlingen" /GRANT:Everyone,change

	setacl -ot file ^
	-actn clear -clr dacl,sacl ^
	-actn ace -ace "n:dbz.lok\admins;s:n;p:full;i:sc,so" ^
	-actn ace -ace "n:dbz.lok\Domain Admins;s:n;p:full;i:sc,so" ^
	-actn ace -ace "n:dbz.lok\studentfolders_read;s:n;p:read;i:sc,so" ^
	-actn setprot -op "dacl:p_nc;sacl:p_nc" ^
	-on "E:\homefolders\leerlingen"

	setacl -ot file ^
	-actn clear -clr dacl,sacl ^
	-actn setprot -op "dacl:np;sacl:np" ^
	-on "E:\homefolders\leerlingen\11"

	setacl -ot file ^
	-actn clear -clr dacl,sacl ^
	-actn rstchldrn -rst dacl,sacl ^
	-actn ace -ace "n:dbz.lok\leerlingt11;s:n;p:change;i:sc,so" ^
	-actn ace -ace "n:dbz.lok\admins;s:n;p:full;i:sc,so" ^
	-actn ace -ace "n:dbz.lok\Domain Admins;s:n;p:full;i:sc,so" ^
	-actn ace -ace "n:dbz.lok\studentfolders_read;s:n;p:read;i:sc,so" ^
	-actn setprot -op "dacl:p_nc;sacl:p_nc" ^
	-on "E:\homefolders\leerlingen\11\leerlingt11"

	setacl -ot file ^
	-actn ace -ace "n:dbz.lok\leerlingt11;s:n;p:delete;i:np;m:deny" ^
	-actn ace -ace "n:dbz.lok\sysweb;s:n;p:read;i:sc,so;m:grant" ^
	-on "E:\homefolders\leerlingen\11\leerlingt11\_www"

	setacl -ot file ^
	-actn ace -ace "n:dbz.lok\leerlingt11;s:n;p:delete;i:np;m:deny" ^
	-actn ace -ace "n:dbz.lok\sys_scan_user;s:n;p:write;i:sc,so;m:grant" ^
	-on "E:\homefolders\leerlingen\11\leerlingt11\_scans"
	*/


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