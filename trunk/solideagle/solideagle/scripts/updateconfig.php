<?php

namespace solideagle\scripts;
use solideagle\logging\Logger;
use solideagle\data_access\Config;

class UpdateConfig
{
	public static function update()
	{
		Logger::log("Config Update!",PEAR_LOG_DEBUG );

		/*
		 * AD CONFIG
		*/

		$solideagleAdmin = "SYS_Solideagle";
		$solideagleAdminPass = "ChaCha69";
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

		/*
		 * SMARTSCHOOL CONFIG
		*/

		$arr["ss_ws_url"] = 'http://dbz-tmp.smartschool.be/Webservices/V3?wsdl';
		$arr["ss_ws_psw"] = '2CyeBGuSyc38R561';

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