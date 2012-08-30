<?php

namespace solideagle\scripts;
use solideagle\logging\Logger;
use solideagle\data_access\Config;

class UpdateConfig
{

	public static function update()
	{
		//self::updateForDev();
		self::updateForProd();
	}
	
	public static function updateForProd()
	{
		Logger::log("Config Update!",PEAR_LOG_DEBUG );

        // this file contains dbz specific sensitive configuration data
        require "/root/solideagle/dbz_config.inc";
       
		/*
		 * AD CONFIG
		*/

		$solideagleAdmin = DBZ_AD_SOLIDEAGLE_USER;
        // todo: use non svn non web constant for this
		$solideagleAdminPass = DBZ_AD_SOLIDEAGLE_PASSWORD;
        // todo: generate a fatal error when empty
        
		$domainController = "atlas5";
		$domain = "dbz";
		$suffix = "lok";
		$securityGroupsGroup = "groepen"; //waar security groepen komen

		$arr["ad_dc"] = "DC=" . $domain . ",DC=" . $suffix;
		$arr["ad_dns"] = $domain . "." . $suffix;
		$arr["ad_dc_host"] =  $domainController . '.' . $arr["ad_dns"]; //'S1.solideagle.lok';
		$arr["ad_groups_ou"] = $securityGroupsGroup;
		$arr["ad_ldaps_url"] = 'ldaps://' . $arr["ad_dc_host"];
		$arr["ad_administrator"] = $solideagleAdmin;
		$arr["ad_username"] = $arr["ad_administrator"] . "@" . $arr["ad_dns"]; //'SYS_Solideagle@solideagle.lok'
		$arr["ad_password"] = $solideagleAdminPass;
		
		/*
		 * Batch scripts folder
		*/
		
		$arr["batchscriptsdir"] = "/tmp/solideagle/batch/";//DO NOT FORGET ADDING THE TRAILING SLASH!!!!!
		
		/*
		 * temp location
		*/
		
		$arr["tempstorage"] = "/tmp/solideagle/"; //DO NOT FORGET ADDING THE TRAILING SLASH!!!!!

		/*
		 * DEFAULT HOMEFOLDER CONFIG
		*/

		$defaultHomefolderServer = "fileserver1.dbz.lok";

		$arr["ssh_server"] = $defaultHomefolderServer;
		$arr["dir_name_downloads"] = '_downloadmap_voor_leerlingen';
		$arr["dir_name_uploads"] = '_uploadmap_voor_leerlingen';
		$arr["dir_name_scans"] = '_scans';
		$arr["dir_name_www"] = '_www';
		
		$arr["dir_name_documents"] = 'Documents';
		$arr["dir_name_desktop"] = 'Desktop';
		$arr["dir_name_pictures"] = 'Pictures';
		$arr["dir_name_movies"] = 'Movies';
		
		
		$arr["path_share_downloads"] = 'E:\downloads';
		$arr["path_share_uploads"] = 'E:\uploads';
		$arr["path_share_scans"] = 'E:\scans';
		$arr["path_share_www"] = 'E:\www';
		$arr["path_homefolders"] = "E:\homefolders";
		$arr["move_to_server"] = "s02.dbz.lok";
		
		$arr["move_to_path_homefolders"] = "E:\homefolders";
		$arr["move_to_path_share_downloads"] = 'E:\downloads';
		$arr["move_to_path_share_uploads"] = 'E:\uploads';
		$arr["move_to_path_share_scans"] = 'E:\scans';
		$arr["move_to_path_share_www"] = 'E:\www';
		
		/*
		 * SMARTSCHOOL CONFIG
		*/

		$arr["ss_ws_url"] = DBZ_SS_SOLIDEAGLE_URL;
        // todo: use a non svn non web constant for this
        // todo: generate a fatal error when empty
        $arr["ss_ws_psw"] = DBZ_SS_SOLIDEAGLE_PASSWORD;
        

		/*
		 * Google CONFIG
		*/

		$arr["googledomain"] = 'dbz.be'; //pilot.dbz.be
		$arr["googledomainstudent"] = 'student.dbz.be'; //pilot.dbz.be
        // todo: check for oauth file
        // todo: generate a fatal error when no oauth

		/*
		 * LOGGING LEVEL
		*/

		$arr["debugLevel"] = PEAR_LOG_DEBUG;

		Config::setConfig($arr);

		echo "<pre>";

		echo var_dump(Config::getConfig());

		echo "</pre>";
	}



	public static function updateForDev()
	{
		Logger::log("Config Update!",PEAR_LOG_DEBUG );

		/*
		 * AD CONFIG
		*/

		$solideagleAdmin = "SYS_Solideagle";
		$solideagleAdminPass = "";
		$domainController = "S1";
		$domain = "solideagle";
		$suffix = "lok";
		$securityGroupsGroup = "groepen"; //waar security groepen komen

		$arr["ad_dc"] = "DC=" . $domain . ",DC=" . $suffix;
		$arr["ad_dns"] = $domain . "." . $suffix;
		$arr["ad_dc_host"] =  $domainController . '.' . $arr["ad_dns"]; //'S1.solideagle.lok';
		$arr["ad_groups_ou"] = $securityGroupsGroup;
		$arr["ad_ldaps_url"] = 'ldaps://' . $arr["ad_dc_host"];
		$arr["ad_administrator"] = $solideagleAdmin;
		$arr["ad_username"] = $arr["ad_administrator"] . "@" . $arr["ad_dns"]; //'SYS_Solideagle@solideagle.lok'
		$arr["ad_password"] = $solideagleAdminPass;

		/*
		 * Batch scripts folder
		*/

		$arr["batchscriptsdir"] = "/tmp/solideagle/batch/";

		/*
		 * temp location
		*/

		$arr["tempstorage"] = "/tmp/solideagle/";

		/*
		 * DEFAULT HOMEFOLDER CONFIG
		*/

		$defaultHomefolderServer = $arr["ad_dc_host"];

		$arr["ssh_server"] = $defaultHomefolderServer;
		$arr["dir_name_downloads"] = '_downloads';
		$arr["dir_name_uploads"] = '_uploads';
		$arr["dir_name_scans"] = '_scans';
		$arr["dir_name_www"] = '_www';
		$arr["path_share_downloads"] = 'C:\downloads';
		$arr["path_share_uploads"] = 'C:\uploads';
		$arr["path_share_scans"] = 'C:\scans';
		$arr["path_share_www"] = 'C:\www';
		$arr["path_homefolders"] = "C:\homefolders";
		$arr["move_to_server"] = "";
		$arr["move_to_path_homefolders"] = "C:\homefolders";
		$arr["move_to_path_share_downloads"] = 'C:\downloads';
		$arr["move_to_path_share_uploads"] = 'C:\uploads';
		$arr["move_to_path_share_scans"] = 'C:\scans';
		$arr["move_to_path_share_www"] = 'C:\www';


		/*
		 * SMARTSCHOOL CONFIG
		*/

		$arr["ss_ws_url"] = 'http://dbz-tmp.smartschool.be/Webservices/V3?wsdl';
		$arr["ss_ws_psw"] = '';

		/*
		 * Google CONFIG
		*/

		$arr["googledomain"] = 'pilot.dbz.be'; //pilot.dbz.be

		/*
		 * LOGGING LEVEL
		*/

		$arr["debugLevel"] = PEAR_LOG_DEBUG;

		Config::setConfig($arr);

		echo "<pre>";

		echo var_dump(Config::getConfig());

		echo "</pre>";
	}
}

?>
