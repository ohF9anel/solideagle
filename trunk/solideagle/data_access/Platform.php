<?php

class Platform
{
    
    // variables
    private $id;
    private $name;
    
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
    
    // manage platforms
    
    public static function addPlatform($platform)
    {
        
    }
    
    public static function updatePlatform($platform)
    {
        
    }
    
    public static function removePlatformById($platformId)
    {
        
    }
    
}

?>
