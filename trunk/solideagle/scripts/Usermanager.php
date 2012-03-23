<?php
namespace scripts;

require_once "scripts/ad/usermanager.php";



class Usermanager
{
	
	public static function Add($person)
	{
		\scripts\ad\usermanager::prepareAddUser($person);
	}
	
}

?>