<?php

namespace solideagle\scripts\exports;


use solideagle\data_access\Group;

use solideagle\data_access\database\DatabaseCommand;

class exportstudents
{
	
	public static function getCSV()
	{
		$studentsgroup = Group::getGroupByName("leerlingen");

		
		$sql = "select p.first_name, p.name, p.account_username, gp.name as groupname , p.account_password, s.passwordcoaccount1,  s.passwordcoaccount2 
				from allpersons p 
				JOIN platform_ss s on p.id = s.person_id 
				JOIN group_closure gc on gc.child_id = p.group_id 
				JOIN `group` gp on gp.id = p.group_id
				WHERE gc.parent_id = :groupid";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":groupid", $studentsgroup->getId());
		
		$csvstring = "Voornaam,Naam,Klas,Gebruikersnaam,Wachtwoord,CoAccount1,CoAccount2\n";
		
		$cmd->executeReader()->readAll(function($data) use (&$csvstring)
		{
			$csvstring.= $data->first_name . "," .$data->name . "," .$data->groupname . "," .$data->account_username . 
			"," .$data->account_password . "," .$data->passwordcoaccount1 . "," .$data->passwordcoaccount2 . ",\n";
		});
		
		return $csvstring;
	}
	
}