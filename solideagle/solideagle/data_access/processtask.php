<?php

namespace solideagle\data_access;

	use solideagle\data_access\database\DatabaseCommand;
	
    class ProcessTask
    {

        // variables

        private $task;
        private $standardConfiguration;

        // getters & setters

        public function getTask()
        {
            return $this->task;
        }

        public function setTask($task)
        {
            $this->task = $task;
        }

        public function getStandardConfiguration()
        {
            return $this->standardConfiguration;
        }

        public function setStandardConfiguration($standardConfiguration)
        {
            $this->standardConfiguration = $standardConfiguration;
        }
        
        function __construct($task, $standardConfiguration)
        {
            $this->task = $task;
            $this->standardConfiguration = $standardConfiguration;
        }

        }
    


?>
