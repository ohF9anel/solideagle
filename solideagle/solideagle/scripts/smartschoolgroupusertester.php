<?php

namespace solideagle\scripts;

use solideagle\data_access\Person;

use solideagle\utilities\XMLParser;

use solideagle\plugins\smartschool\data_access\Api;

class smartschoolgroupusertester
{
	public static function doTest()
	{


		$api = Api::singleton();

		$parser = new XMLParser();

		$staffusers = array();

		$parser->parse(base64_decode($api->getAllAccounts("x1xstaff",0)),function($elem,$data) use (&$staffusers){
			if($elem == "GEBRUIKERSNAAM")
			{
				$staffusers[$data]=$data;
					
			}
		});
		
	

		$parser->parse(base64_decode($api->getAllAccounts("x1xLeerkrachten",0)),function($elem,$data) use (&$staffusers){
			if($elem == "GEBRUIKERSNAAM")
			{
				$person = Person::getPersonByUsername($data);
				if($person != NULL)
				{
					if($person->getGroupId() == 112)
					{
						echo "OK: " . $data . " is lkr\n";
					}else if($person->getGroupId() == 111 && isset($staffusers[$data])){

						echo "OK2: " . $data . " is staff\n"; 
					}else{
						echo "WARN: " . $data . " is in " . $person->getGroupId() ."\n" ;
					}
				}else{
					echo "ERR: " . $data . " bestaat niet!\n" ;
				}
			}
		});
	}
}