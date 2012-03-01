<?php
	
class Group{
	private $id;
	private $name;
	private $subGroups = array();
	
	public function __construct($id,$name) {
		$this->id = $id;
		$this->name = $name;
	}
	
	public function addSubGroup($group)
	{
		$this->subGroups[] = $group;
	}
	
	public function getSubGroups()
	{
		return $this->subGroups;
	}	

	public function getId()
	{
	    return $this->id;
	}

	public function getName()
	{
	    return $this->name;
	}

	public function setName($name)
	{
	    $this->name = $name;
	}
}
?>