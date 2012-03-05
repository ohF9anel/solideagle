<?php

namespace DataAccess
{

    require_once '../data_access/database/databasecommand.php';
    use Database\DatabaseCommand;
    
    class TaskType
    {
        
        // variables
        private $name;

        // getters & setters

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
                        `name`
                        )
                        VALUES
                        (
                        :name
                        );
                        ";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":name", $taskType->getName());
                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->CommitTransaction();
                return $retval;
        }
        
        public static function delTaskTypeByName($taskTypeName)
        {
            $sql = "DELETE FROM `CentralAccountDB`.`tasktype`
                    WHERE `name` = :name;";

            $cmd = new DatabaseCommand($sql);
            $cmd->addParam(":name", $taskTypeName);

            $cmd->execute();
        }

    }
    
}
?>
