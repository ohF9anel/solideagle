<?php

class PersonTask
{
    
    // variables
    private $personId;
    private $taskId;
    private $configuration;
    
    // getters & setters
    
    public function getPersonId()
    {
        return $this->personId;
    }

    public function setPersonId($personId)
    {
        $this->personId = $personId;
    }

    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    public function getConfiguration()
    {
        return $this->configuration;
    }

    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
    }
    
    // manage person tasks
    
    public static function addPersonTask($personTask)
    {
        
    }
    
    public static function removePersonTaskById($personTaskId)
    {
        
    }
    
}

?>
