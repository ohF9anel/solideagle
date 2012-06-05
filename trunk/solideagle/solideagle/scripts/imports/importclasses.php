<?php
namespace solideagle\scripts\imports;

use solideagle\data_access\Type;

use solideagle\scripts\groupmanager;

use solideagle\data_access\Group;

class importclasses
{
	public static function getNotExistingClasses($arr)
	{
		$retarr = array();
		
		foreach($arr as $classobj)
		{
			$class = Group::getGroupByName($classobj->name);
			if($class === NULL)
			{
				
				switch (substr($classobj->name,0,1)) {
					case 1:
						$classobj->instellingsnummer = "112144";
						break;
					case 2:
						$classobj->instellingsnummer = "112144";
						break;
					case 3:
						$classobj->instellingsnummer = "038604";
						break;
					case 4:
						$classobj->instellingsnummer = "038604";
						break;
					case 5:
						$classobj->instellingsnummer = "038604";
						break;
					case 6:
						$classobj->instellingsnummer = "038604";
						break;
				}
				
				$retarr[$classobj->name] = $classobj;
			}
		}
		
		return $retarr;
	}
	
	public static function createClasses($arr)
	{
		$studentsParentGroup = Group::getGroupByName("leerlingen"); 
	
		if($studentsParentGroup === NULL)
		{
			echo "FOUT: De groep leerlingen bestaat niet!";
			return;
		}
	
		$groupStructureResults = new \stdClass();
	
		//Check if structure of groups exists
		foreach(Group::getChilderen($studentsParentGroup) as $childGroup)
		{
			if($childGroup->getName() == "leerlingen graad 1")
			{
				$groupStructureResults->eerstegraad = $childGroup;
				foreach(Group::getChilderen($childGroup) as $subchildGroup)
				{
					if($subchildGroup->getName() == "eerstes")
					{
						$groupStructureResults->eerstes = $subchildGroup;
					}else if($subchildGroup->getName() == "tweedes")
					{
						$groupStructureResults->tweedes = $subchildGroup;
					}
				}
			}
			else if($childGroup->getName() == "leerlingen graad 2")
			{
				$groupStructureResults->tweedegraad = $childGroup;
				foreach(Group::getChilderen($childGroup) as $subchildGroup)
				{
					if($subchildGroup->getName() == "derdes")
					{
						$groupStructureResults->derdes = $subchildGroup;
					}else if($subchildGroup->getName() == "vierdes")
					{
						$groupStructureResults->vierdes = $subchildGroup;
					}
				}
			}
			else if($childGroup->getName() == "leerlingen graad 3")
			{
				$groupStructureResults->derdegraad = $childGroup;
				foreach(Group::getChilderen($childGroup) as $subchildGroup)
				{
					if($subchildGroup->getName() == "vijfdes")
					{
						$groupStructureResults->vijfdes = $subchildGroup;
					}else if($subchildGroup->getName() == "zesdes")
					{
						$groupStructureResults->zesdes = $subchildGroup;
					}
				}
			}
		}
	
		//create structure if necessary
	
		if(!isset($groupStructureResults->eerstegraad ))
		{
			$group = new Group();
			$group->setName("leerlingen graad 1");
			$group->setParentId($studentsParentGroup->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->eerstegraad = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->tweedegraad ))
		{
			$group = new Group();
			$group->setName("leerlingen graad 2");
			$group->setParentId($studentsParentGroup->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->tweedegraad = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->derdegraad ))
		{
			$group = new Group();
			$group->setName("leerlingen graad 3");
			$group->setParentId($studentsParentGroup->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->derdegraad = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->eerstes ))
		{
			$group = new Group();
			$group->setName("eerstes");
			$group->setParentId($groupStructureResults->eerstegraad->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->eerstes = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->tweedes ))
		{
			$group = new Group();
			$group->setName("tweedes");
			$group->setParentId($groupStructureResults->eerstegraad->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->tweedes = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->derdes ))
		{
			$group = new Group();
			$group->setName("derdes");
			$group->setParentId($groupStructureResults->tweedegraad->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->derdes = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->vierdes ))
		{
			$group = new Group();
			$group->setName("vierdes");
			$group->setParentId($groupStructureResults->tweedegraad->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->vierdes = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->vijfdes ))
		{
			$group = new Group();
			$group->setName("vijfdes");
			$group->setParentId($groupStructureResults->derdegraad->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->vijfdes = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		if(!isset($groupStructureResults->zesdes ))
		{
			$group = new Group();
			$group->setName("zesdes");
			$group->setParentId($groupStructureResults->derdegraad->getId());
			$gid = Group::addGroup($group);
			$group->setId($gid);
			$groupStructureResults->zesdes = $group;
			groupmanager::Add(Group::getParents($group), $group);
		}
	
		foreach($arr as $klas)
		{
			$group = new Group();
	
			switch (substr($klas->name,0,1)) {
				case 1:
					$group->setParentId($groupStructureResults->eerstes->getId());
					break;
				case 2:
					$group->setParentId($groupStructureResults->tweedes->getId());
					break;
				case 3:
					$group->setParentId($groupStructureResults->derdes->getId());
					break;
				case 4:
					$group->setParentId($groupStructureResults->vierdes->getId());
					break;
				case 5:
					$group->setParentId($groupStructureResults->vijfdes->getId());
					break;
				case 6:
					$group->setParentId($groupStructureResults->zesdes->getId());
					break;
	
				default:
					$group->setParentId($studentsParentGroup->getId());
					break;
			}
	
			$group->setName($klas->name);
			$group->setAdministrativeNumber($klas->administratievegroep);
			$group->setInstituteNumber($klas->instellingsnummer);
			$group->addType(new Type(Type::TYPE_LEERLING));
	
			$newgroupid = Group::addGroup($group);
	
			$group->setId($newgroupid);
	
			groupmanager::Add(Group::getParents($group), $group);
		}
	}
}