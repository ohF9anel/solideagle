<?php
namespace solideagle\scripts;



class Usermanager
{
	
	public static function Add($person)
	{
		ad\usermanager::prepareAddUser($person);
	}
	
}

?>