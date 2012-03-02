<?php

namespace DataAccess
{
	
	require_once 'database/databasecommand.php';
	use Database\DatabaseCommand;
    
    class Course
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

       

        /**
        * My function description.
        * Returns inserted ID
        *
        * @param Course $course
        * @return int
        */
        public static function addCourse($course)
        {
			$sql = "INSERT INTO `CentralAccountDB`.`course`
			(`name`)
			VALUES
			(:name);";
			
			
			$cmd = new DatabaseCommand($sql);
			$cmd->addParam(":name", $course->getName());
			
			$cmd->BeginTransaction();
			
			$cmd->execute();
			
			$cmd->newQuery("SELECT LAST_INSERT_ID();");
			
			$retval =  $cmd->executeScalar();
			
			$cmd->CommitTransaction();
			
			return $retval;

			
        }

        public static function updateCourse($course)
        {

        }
        
        

        public static function delCourseById($courseId)
        {

        }
    }
}

?>
