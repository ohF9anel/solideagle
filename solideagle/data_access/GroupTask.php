<?php

namespace DataAccess
{

    class GroupTask
    {

        // variables
        private $id;
        private $groupId;
        private $taskId;
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
                $sql = "INSERT INTO `CentralAccountDB`.`group_task_queue`
                        (
                                `id`,
                                `task_id`,
                                `group_id`,
                                `configuration`
                        )
                        VALUES
                        (
                                :id,
                                :task_id,
                                :group_id,
                                :configuration
                        );";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $groupTask->getId());
                $cmd->addParam(":task_id", $groupTask->getTaskId());
                $cmd->addParam(":group_id", $groupTask->getGroupId());
                $cmd->addParam(":configuration", $groupTask->getConfiguration());

                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $retval = $cmd->executeScalar();

                $cmd->CommitTransaction();
                return $retval;
        }

        public static function delGroupTaskById($groupTaskId)
        {
                $sql = "DELETE FROM `CentralAccountDB`.`group_task_queue`
					WHERE `id` = :id;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $groupTaskId);

                $cmd->execute();
        }

    }
    
}

?>
