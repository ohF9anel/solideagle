<?php

namespace DataAccess
{

    class Process
    {

        // variables
        private $id;
        private $name;
        private $tasks = array();

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

        public function getTasks()
        {
            return $this->tasks;
        }

        public function addTask($taskId)
        {
            $this->tasks[] = $taskId;
        }

        public function delTasks($arrTaskIds)
        {
            $this->tasks = array_diff($this->tasks, $arrTaskIds);
        }

    }
    
}

?>
