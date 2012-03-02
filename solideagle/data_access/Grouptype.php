<?php



namespace DataAccess;



class GroupType
{

	// variables
	private $id;
	private $name;
	private $platform;

	// getters & setters

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getName()
	{
		return $this->name;
	}

	public function setName($name)
	{
		$this->name = $name;
	}

	// manage group types

	public static function addGroupType($groupType)
	{

	}

	public static function updateGroupType($groupType)
	{

	}

	public static function delGroupTypeById($groupTypeId)
	{

	}

}



?>