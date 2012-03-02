<?php

namespace DataAccess
{

    class GroupTask
    {

        // variables
        private $groupId;
        private $taskId;
        private $configuration;

        // getters & setters

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

        // manage group tasks

        public static function addGroupTask($groupTask)
        {

        }

        public static function delGroupTaskById($groupTaskId)
        {

        }

    }
    
}

?>
