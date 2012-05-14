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
	}

	public function setDesc($desc)
	{
	    $this->desc = $desc;
	}

	/**
	 * This field is used as the unique identifier for groups on smartschool, we use the name of our group
	 * @param string $code
	 */
	public function setCode($code)
	{
	    $this->code = self::GroupPrefix . UnicodeHelper::cleanSmartschoolCodeString($code);
	}

	public function setParentCode($parentCode) 
	{
	    $this->parentCode = self::GroupPrefix . UnicodeHelper::cleanSmartschoolCodeString($parentCode);
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
		$retval = 0;
		
		$api = Api::singleton();
		$retval += $api->saveClass($classGroup->name, $classGroup->desc, $classGroup->code,$classGroup->parentCode,		
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
		$retval = 0;
				
		$api = Api::singleton();
		$retval += $api->delClass(self::GroupPrefix . UnicodeHelper::cleanSmartschoolCodeString($classGroupCode));
		
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
	
	
	public static function moveClassGroup($newparentgroupcode,$classgroup)
	{
		//not supported by smartschool!
	}

	
}

?>