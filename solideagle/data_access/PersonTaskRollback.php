<?php

namespace DataAccess
{

    class PersonTaskRollback
    {

        // variables
        private $id;
        private $personId;
        private $taskId;
        private $configuration;
        private $taskExecutedOn;

        // getters & setters 
        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }

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

        public function getTaskExecutedOn()
        {
            return $this->taskExecutedOn;
        }

        public function setTaskExecutedOn($taskExecutedOn)
        {
            $this->taskExecutedOn = $taskExecutedOn;
        }

        public static function addPersonTaskRollback($personTaskRollback)
        {

        }

        public static function delPersonTaskRollbacksStartingFromId($personTaskRollbackStartingId)
        {

        }

    }

}
    
?>
