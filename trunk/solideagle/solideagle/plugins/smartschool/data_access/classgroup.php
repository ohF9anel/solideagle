<?php
namespace solideagle\plugins\smartschool\data_access;


use solideagle\data_access\helpers\UnicodeHelper;

use solideagle\plugins\StatusReport;

class ClassGroup{

	const GroupPrefix = "x1x";
	
	private $name;
	private $desc;
	private $code;
	private $parentCode; //ClassGroup
	private $untis;
	private $instituteNumber;
	private $adminNumber;
	
	public function setName($name)
	{
	    $this->name = $name;
	    
	    //The field code is used as the unique identifier for groups on smartschool, we use the name of our group
	    $this->setCode($name);
	}

	public function setDesc($desc)
	{
	    $this->desc = $desc;
	}

	/**
	 * This field is used as the unique identifier for groups on smartschool
	 * @param string $code
	 */
	private function setCode($code)
	{
	    $this->code = self::createGroupcodeFromName($code);
	}

	public function setParentCode($parentCode) 
	{
	    $this->parentCode = self::createGroupcodeFromName($parentCode);
	}

	public function setUntis($untis)
	{
	    $this->untis = $untis;
	}

	public function setInstituteNumber($instituteNumber)
	{
	    $this->instituteNumber = $instituteNumber;
	}

	public function setAdminNumber($adminNumber)
	{
	    $this->adminNumber = $adminNumber;
	}
	
	public static function saveClassGroup($classGroup)
	{
		$api = Api::singleton();
		$retval = $api->saveClass($classGroup->name, $classGroup->desc, $classGroup->code,$classGroup->parentCode,		
									$classGroup->untis, $classGroup->instituteNumber, $classGroup->adminNumber);
		
		if($retval == 0)
		{
			return new StatusReport();
		}else{
			return new StatusReport(false,Api::getErrorFromCode($retval));
		}

	}
	
	public static function deleteClassGroupByCode($classGroupCode)
	{
			
		$api = Api::singleton();
		$retval = $api->delClass(self::createGroupcodeFromName($classGroupCode));
		
		if($retval == 0)
		{
			return new StatusReport();
		}else{
			return new StatusReport(false,Api::getErrorFromCode($retval));
		}
	}

	public static function updateClassGroup()
	{
		//not supported by smartschool!
		
	}
	
	
	public static function moveClassGroup($classgroup)
	{
		//not supported by smartschool!
		//trying to set the parentcode does nothing
		return;
		
		//there is only one API function for smartschool
		saveClassGroup($classgroup);
	}
	
	private static function createGroupcodeFromName($name)
	{
		return self::GroupPrefix . UnicodeHelper::cleanSmartschoolCodeString($name);
	}

	
}

?>