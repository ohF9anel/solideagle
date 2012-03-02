<?php

namespace DataAccess
{

    require_once '../data_access/database/databasecommand.php';
    use Database\DatabaseCommand;

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
        
        // manage tasks
        
        public static function addTask($task)
        {
                $sql = "INSERT INTO `CentralAccountDB`.`task`
                        (
                                `id`,
                                `name`,
                                `path_script`,
                                `tasktype_id`
                        )
                        VALUES
                        (
                                :id,
                                :name,
                                :path_script,
                                :tasktype_id 
                        );";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $task->getId());
                $cmd->addParam(":name", $task->getName());
                $cmd->addParam(":path_script", $task->getPathScript());
                $cmd->addParam(":tasktype_id", $task->getTaskTypeId());

                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $retval = $cmd->executeScalar();

                $cmd->CommitTransaction();
                return $retval;
        }

        public static function delTaskById($taskId)
        {
            $sql = "DELETE FROM `CentralAccountDB`.`task`
					WHERE `id` = :id;";

            $cmd = new DatabaseCommand($sql);
            $cmd->addParam(":id", $taskId);

            $cmd->execute();
        }
    }
    
}

?>
