<?php

namespace DataAccess
{

    class TaskType
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

    }

    class Task
    {

        // variables
        private $id;
        private $name;
        private $pathScript;
        private $taskTypeId;
        private $configuration;

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

        public function getPathScript()
        {
            return $this->pathScript;
        }

        public function setPathScript($pathScript)
        {
            $this->pathScript = $pathScript;
        }

        public function getTaskTypeId()
        {
            return $this->taskTypeId;
        }

        public function setTaskTypeId($taskTypeId)
        {
            $this->taskTypeId = $taskTypeId;
        }

        public function getConfiguration()
        {
            return $this->configuration;
        }

        public function setConfiguration($configuration)
        {
            $this->configuration = $configuration;
        }

    }
    
}

?>
