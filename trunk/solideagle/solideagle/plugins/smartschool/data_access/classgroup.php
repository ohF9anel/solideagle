<?php
namespace solideagle\plugins\smartschool;
require_once 'Api.php';

class ClassGroup{

	private $name;
	private $desc;
	private $code;
	private $parent; //ClassGroup
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

	public function setCode($code)
	{
	    $this->code = $code;
	}

	public function setParent($parent) //ClassGroup
	{
	    $this->parent = $parent;
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
		
		assert('isset($classGroup->name) /* name required!*/');
		assert('isset($classGroup->desc) /* desc required!*/');
		assert('isset($classGroup->code) /* code required!*/');
		
		$api = Api::singleton();
		$retval += $api->saveClass($classGroup->name, $classGroup->desc, $classGroup->code,
									isset($classGroup->parent)?$classGroup->parent->code:NULL,		
									$classGroup->untis, $classGroup->instituteNumber, $classGroup->adminNumber);

		return $retval;
	}
	
	public static function deleteClassGroup($classGroup)
	{
		$retval = 0;
				
		assert('isset($classGroup->code) /* code required!*/');
		
		$api = Api::singleton();
		$retval += $api->delClass($classGroup->code);
		
		return $retval;
	}

	

	
}

?>