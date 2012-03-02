<?php

namespace DataAccess
{

    class GroupTaskRollback
    {

        // variables
        private $id;
        private $groupId;
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

        public function getGroupId()
        {
            return $this->groupId;
        }

        public function setGroupId($groupId)
        {
            $this->groupId = $groupId;
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

        public static function addGroupTaskRollback($groupTaskRollback)
        {

        }

        public static function delGroupTaskRollbacksStartingFromId($groupTaskRollbackStartId)
        {

        }

    }
    
}
    
?>
