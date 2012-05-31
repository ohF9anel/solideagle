<?php
namespace solideagle\plugins\ad;

use solideagle\Config;

class homefolderPlugin
{

	private $conn;

	public function __construct($server)
	{
		$this->conn = sshpreformatter::singleton()->getFileForServer($server);
	}


	public function createHomeFolder($username,$path,$wwwJunctionPath,$scanJunctionPath)
	{

		$this->conn->write("mkdir " . $path); //create path for user
			
		$homedirwwwpath = $path . "\\" . Config::singleton()->dir_name_www;
		$homedirscanpath = $path .  "\\" . Config::singleton()->dir_name_scans;
			
		$this->conn->write("mkdir " . $homedirwwwpath); //create www path
		$this->conn->write("mkdir " . $homedirscanpath); //create scan path
			
		$this->conn->write("setacl -ot file ^
				-actn clear -clr dacl,sacl ^
				-actn rstchldrn -rst dacl,sacl ^
				-actn ace -ace \"n:dbz.lok\\".$username.";s:n;p:change;i:sc,so\" ^
				-actn ace -ace \"n:dbz.lok\\admins;s:n;p:full;i:sc,so\" ^
				-actn ace -ace \"n:dbz.lok\\Domain Admins;s:n;p:full;i:sc,so\" ^
				-actn ace -ace \"n:dbz.lok\\studentfolders_read;s:n;p:read;i:sc,so\" ^
				-actn setprot -op \"dacl:p_nc;sacl:p_nc\" ^
				-on \"". $path ."\"");
			
		$this->conn->write("setacl -ot file ^
				-actn ace -ace \"n:dbz.lok\\".$username.";s:n;p:delete;i:np;m:deny\" ^
				-actn ace -ace \"n:dbz.lok\\sysweb;s:n;p:read;i:sc,so;m:grant\" ^
				-on \"". $homedirwwwpath ."\"");
			
		$this->conn->write("setacl -ot file ^
				-actn ace -ace \"n:dbz.lok\\".$username.";s:n;p:delete;i:np;m:deny\" ^
				-actn ace -ace \"n:dbz.lok\\sys_scan_user;s:n;p:read;i:sc,so;m:grant\" ^
				-on \"". $homedirscanpath ."\"");

		$this->conn->write("net share ".$username."$=\"". $path ."\" /GRANT:".$username.",change");
		
		$this->conn->write("mkdir " . $wwwJunctionPath); //create junction path
		
		$this->conn->write("mklink /j \"" . $wwwJunctionPath .  "\\" . $username . "\" \"" . $homedirwwwpath . "\"");
		$this->conn->write("mklink /j \"" . $scanJunctionPath . "\" \"" . $homedirscanpath . "\"");
		
	}
	
	public function addUpDownToHomeFolder($username,$path)
	{
		$homedirdownloadpath = $path . "\\" . Config::singleton()->dir_name_downloads;
		$homediruploadpath = $path .  "\\" . Config::singleton()->dir_name_scans;
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




}