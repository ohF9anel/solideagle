<?php

class ProcessTask
{
 
    // variables
    
    private $taskId;
    private $processId;
    private $standardConfiguration;
    
    // getters & setters
    
    public function getTaskId()
    {
        return $this->taskId;
    }

    public function setTaskId($taskId)
    {
        $this->taskId = $taskId;
    }

    public function getProcessId()
    {
        return $this->processId;
    }

    public function setProcessId($processId)
    {
        $this->processId = $processId;
    }

    public function getStandardConfiguration()
    {
        return $this->standardConfiguration;
    }

    public function setStandardConfiguration($standardConfiguration)
    {
        $this->standardConfiguration = $standardConfiguration;
    }
    
}

?>
