<?php

namespace DataAccess
{

    require_once '../data_access/database/databasecommand.php';
    use Database\DatabaseCommand;
    
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
        
        // manage task types
        
        /**
         *
         * @param TaskType $taskType
         * @return int id 
         */
        public static function addTaskType($taskType)
        {
                $sql = "INSERT INTO `CentralAccountDB`.`tasktype`
                        (
                        `id`,
                        `name`
                        )
                        VALUES
                        (
                        :id,
                        :name
                        );
                        ";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $taskType->getId());
                $cmd->addParam(":name", $taskType->getName());
                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $retval = $cmd->executeScalar();

                $cmd->CommitTransaction();
                return $retval;
        }
        
        public static function delTaskTypeById($taskTypeId)
        {
            $sql = "DELETE FROM `CentralAccountDB`.`tasktype`
                    WHERE `id` = :id;";

            $cmd = new DatabaseCommand($sql);
            $cmd->addParam(":id", $taskTypeId);

            $cmd->execute();
        }

    }
    
}
?>
