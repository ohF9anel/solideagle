<?php

namespace DataAccess
{

    class PersonTask
    {

        // variables
        private $personId;
        private $taskId;
        private $configuration;

        // getters & setters

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

        // manage person tasks

        public static function addPersonTask($personTask)
        {
                $sql = "INSERT INTO `CentralAccountDB`.`person_task_queue`
                        (
                                `id`,
                                `person_id`,
                                `task_id`,
                                `configuration`
                        )
                        VALUES
                        (
                                :id,
                                :person_id,
                                :task_id,
                                :configuration
                        );";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $personTask->getId());
                $cmd->addParam(":person_id", $personTask->getPersonId());
                $cmd->addParam(":task_id", $personTask->getTaskId());
                $cmd->addParam(":configuration", $personTask->getConfiguration());

                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $retval = $cmd->executeScalar();

                $cmd->CommitTransaction();
                return $retval;
        }

        public static function delPersonTaskById($personTaskId)
        {
                $sql = "DELETE FROM `CentralAccountDB`.`person_task_queue`
					WHERE `id` = :id;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $personTaskId);

                $cmd->execute();
        }

    }
    
}

?>
