<?php

namespace AD;

class Group
{
    
    //properties - same as AD attributes
    private $members = array();
    private $name;
    
    // add new member to group array
    public function addMember($member)
    {
        $this->member[] = $member;
    }
    
    public function getMembers()
    {
        return $this->member;
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
