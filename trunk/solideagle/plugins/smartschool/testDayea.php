<?php

$time_start = microtime(true);


require_once '../../data_acces/Group.php';
require_once '../../data_acces/User.php';

//generate some data

$group = new Group(0,"__ParentGroupT4");

$arrofgroup = array($group);

$arrofusers = array();

for($i = 1;$i<6;$i++)
{
	$subGroup = new Group($i,"ChildGroupT4" . $i);	
	
	for($y = 1;$y<21;$y++)
	{
		$user = new User($i.$y);		
		$user->setUsername("userT4" .$i . $y);
		$user->setFirstname("user");
		$user->setLastname("".$i.$y);
		$user->setPassword("12345");
		$user->addToGroup($subGroup);
		$arrofusers[] = $user;
	}	
	$group->addSubGroup($subGroup);
}



//use smartschool_plugin to create this data

require_once 'config.php';
require_once 'data_acces/ClassGroup.php';
require_once 'data_acces/User.php';

function createGroups($groups,$parent = NULL) //array of groups, ClassGroup parent
{
	foreach ($groups as $fgroup)
	{
		$classgroup = new Smartschool\ClassGroup();
		$classgroup->setName($fgroup->getName());
		$classgroup->setCode("SS_" . $fgroup->getName());
		$classgroup->setDesc($fgroup->getName());
		$classgroup->setParent($parent);
		echo Smartschool\Error::getErrorFromCode(Smartschool\ClassGroup::saveClassGroup($classgroup)) . " " .$fgroup->getName(). "\n";
		createGroups($fgroup->getSubGroups(),$classgroup);
	}	
}

function createUsers($users)
{
	foreach($users as $fuser)
	{
		$ssuser = new Smartschool\User();
		$ssuser->setInternnumber("SSI_" . $fuser->getId());
		$ssuser->setUsername($fuser->getUsername());
		$ssuser->setPasswd1($fuser->getPassword());
		$ssuser->setName($fuser->getFirstName());
		$ssuser->setSurname($fuser->getLastName());
		
		$ssuser->setBasisrol("leerling");
		$ssuser->setAccountStatus("actief");
		
		foreach($fuser->getGroups() as $fgroup)
		{
			$ssuser->addClass("SS_" . $fgroup->getName());
		}
		
		echo Smartschool\Error::getErrorFromCode(Smartschool\User::saveUser($ssuser)). " " . $fuser->getId() . "\n";
	}
}

createGroups($arrofgroup);

createUsers($arrofusers);

echo "\n";

$time_end = microtime(true);
$time = $time_end - $time_start;

echo "Script ran in $time seconds\n";

echo "\n";
echo "Peak memory usage was: " . (memory_get_peak_usage(true)/1000) . "kb";



?>