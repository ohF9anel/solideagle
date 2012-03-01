<?php
	
class Group
{
    
    // variables
    private $id;
    private $name;
    private $descriptionRights;
    private $childGroups = array();
    
    // getters, setters & functions
    
    public function addChildGroup($childGroup)
    {
            $this->children[] = $childGroup;
    }

    public function getChildGroups()
    {
            return $this->childGroups;
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
    
    public function getDescriptionRights()
    {
        return $this->descriptionRights;
    }

    public function setDescriptionRights($descriptionRights)
    {
        $this->descriptionRights = $descriptionRights;
    }

}

?>