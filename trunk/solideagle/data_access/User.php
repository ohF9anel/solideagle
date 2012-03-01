<?php

namespace DataAccess
{

    class User
    {
            private $id;
            private $username;
            private $firstname;
            private $lastname;
            private $password;
            private $groups = array();

            public function __construct($id)
            {
                $this->id = $id;
            }

            public function addToGroup($group)
            {
                $this->groups[] = $group;
            }

            public function getGroups()
            {
                return $this->groups;
            }

            public function getId()
            {
                return $this->id;
            }

            public function getUsername()
            {
                return $this->username;
            }

            public function setUsername($username)
            {
                $this->username = $username;
            }

            public function getFirstname()
            {
                return $this->firstname;
            }

            public function setFirstname($firstname)
            {
                $this->firstname = $firstname;
            }

            public function getLastname()
            {
                return $this->lastname;
            }

            public function setLastname($lastname)
            {
                $this->lastname = $lastname;
            }

            public function getPassword()
            {
                return $this->password;
            }

            public function setPassword($password)
            {
                $this->password = $password;
            }




    }
    
}

?>