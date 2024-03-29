<?php

namespace solideagle\plugins\ga;



class errorhandler
{
    protected $errorMessages;
    
    public function __construct()
    {
        $errorMessages = array();
    }
    
    public function addGappsError($error)
    {
        {
            $this->errorMessages[] = $error;
        }
    }
    
    public function hasErrors()
    {
        if (sizeof($this->errorMessages) > 0)
        {
            return true;
        }
        else
        {
            return false;
        }
    }
    
    public function toString()
    {
        $string = "";
        
        if (!$this->hasErrors())
            return $string;
        
        foreach($this->errorMessages as $msg)
        {
            $string .= $msg . "; ";
        }
        
        return substr($string, 0, -2);
    }
    
}

?>
