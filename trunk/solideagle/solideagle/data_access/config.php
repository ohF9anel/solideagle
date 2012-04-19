<?php

namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;

class Config
{
	public static function getConfig()
	{
		$sql = "SELECT
				`config`.`config`
				FROM  `config`;";
		$cmd = new DatabaseCommand($sql);
		
		$compressedConfig = $cmd->executeReader()->read();
		
		$retval = unserialize(base64_decode($compressedConfig->config));
		
		return  $retval;
		
	}
	
	public static function setConfig($conf)
	{
		$sql = "UPDATE  `config` SET `config` = :config;";
		$cmd = new DatabaseCommand($sql);
		
		$conf = base64_encode(serialize($conf));
		
		$cmd->addParam(":config", $conf);
		
		$cmd->execute();
	}
}