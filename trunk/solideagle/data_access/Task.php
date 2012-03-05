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
        private $taskType;
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

        public function getTaskType()
        {
            return $this->taskType;
        }

        public function setTaskType($taskType)
        {
            $this->taskType = $taskType;
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
                                `task_type`
                        )
                        VALUES
                        (
                                :id,
                                :name,
                                :path_script,
                                :task_type
                        );";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $task->getId());
                $cmd->addParam(":name", $task->getName());
                $cmd->addParam(":path_script", $task->getPathScript());
                $cmd->addParam(":task_type", $task->getTaskType());

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
