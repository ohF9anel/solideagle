<?php

namespace DataAccess
{
    
    require_once '../data_access/database/databasecommand.php';
    require_once '../data_access/ProcessTask.php';
    use Database\DatabaseCommand;

    class Process
    {

        // variables
        private $id;
        private $name;
        private $tasks = array();

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

        public function getTasks()
        {
            return $this->tasks;
        }

        public function addTask($task, $standardConfiguration = null)
        {
            $this->tasks[] = new ProcessTask($task, $standardConfiguration);
        }

        public function delTasks($arrTaskIds)
        {
            $this->tasks = array_diff($this->tasks, $arrTaskIds);
        }
        
        // manage processes
        
        /**
         * Adds a process with its tasks
         * @param Process $process
         * @return int id 
         */
        public static function addProcess($process)
        {
                // add new process
            
                $sql = "INSERT INTO `CentralAccountDB`.`process`
                        (
                                `id`,
                                `name`
                        )
                        VALUES
                        (
                                :id,
                                :name
                        );";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $process->getId());
                $cmd->addParam(":name", $process->getName());

                $cmd->BeginTransaction();

                $cmd->execute();
                
                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $processId = $cmd->executeScalar();

                // add tasks to process
                
                $sql = "INSERT INTO `CentralAccountDB`.`process_task`
                        (
                                `task_id`,
                                `process_id`,
                                `standard_configuration`
                        )
                        VALUES
                        (
                                :task_id,
                                :process_id,
                                :standard_configuration
                        );";
                
                foreach($process->getTasks() as $task) {
                        $cmd = new DatabaseCommand($sql);
                        $cmd->addParam(":task_id", $task->getTask()->getId());
                        $cmd->addParam(":process_id", $processId);
                        $cmd->addParam(":standard_configuration", $task->getStandardConfiguration());

                        $cmd->execute();
                }
                

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $retval = $cmd->executeScalar();

                $cmd->CommitTransaction();
                
                return $retval;
        }
        
        /**
         *
         * @param int $processId 
         */
        public static function delProcessById($processId)
        {
                $sql = "DELETE FROM `CentralAccountDB`.`process`
					WHERE `id` = :id;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $processId);

                $cmd->execute();
        }

    }
    
}

?>
