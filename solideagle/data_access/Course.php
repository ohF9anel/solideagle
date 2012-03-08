<?php

namespace DataAccess
{

    require_once 'database/databasecommand.php';
    require_once 'validation/Validator.php';
    use Database\DatabaseCommand;
    use Validation\Validator;
    use Validation\ValidationError;

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
                        if(!Course::validateCourse($course))
                            return false;
                    
			$sql = "INSERT INTO `CentralAccountDB`.`course`
			(
				`name`
			)
			VALUES
			(
				:name
			);";

			$cmd = new DatabaseCommand($sql);
			$cmd->addParam(":name", $course->getName());


			$cmd->BeginTransaction();

			$cmd->execute();

			$cmd->newQuery("SELECT LAST_INSERT_ID();");

			$retval =  $cmd->executeScalar();

			$cmd->CommitTransaction();
			return $retval;
		}

		/**
		 * 
		 *
		 * @param Course $course
		 */
		public static function updateCourse($course)
		{
			$sql = "UPDATE `CentralAccountDB`.`course`
				SET
				`name` = :name
				WHERE 
				`id` = :id;";

			$cmd = new DatabaseCommand($sql);
			$cmd->addParam(":id", $course->getId());
			$cmd->addParam(":name", $course->getName());

			$cmd->execute();
		}



		public static function delCourseById($courseId)
		{
				
			$sql = "DELETE FROM `CentralAccountDB`.`course`
					WHERE `id` = :id;";

			$cmd = new DatabaseCommand($sql);
			$cmd->addParam(":id", $courseId);

			$cmd->execute();

		}
		
		public static function getAllCourses()
		{
			$retArr = array();

			$sql = "SELECT
					`course`.`id`,
					`course`.`name`
					FROM `CentralAccountDB`.`course`;";
			
			$cmd = new DatabaseCommand($sql);
			
			$reader = $cmd->executeReader();
			
			$reader->readAll(function($courseRow) use (&$retArr) {
				
				$tempCourse = new Course();
				$tempCourse->setId($courseRow->id);
				$tempCourse->setName($courseRow->name);
			
				
				$retArr[] = $tempCourse;
				
			});
			
			return $retArr;
		}
                
                public static function validateCourse($course)
                {
                    $validationErrors = array();
                    $valErrors = Validator::validateString($course->getName(), 1, 45);
                    foreach ($valErrors as $valError)
                    {
                        switch($valError) {
                            case ValidationError::IS_NULL;
                                $validationErrors[] = "Naam: geef een naam in."; break;
                            case ValidationError::STRING_TOO_LONG:
                                $validationErrors[] = "Naam: mag niet langer zijn dan 45 karakters."; break;
                            case ValidationError::STRING_HAS_SPECIAL_CHARS:
                                $validationErrors[] = "Naam: mag geen speciale tekens bevatten."; break;
                            default:
                                $validationErrors[] = "Naam: fout."; break;
                        }
                    }
                    
                    return $validationErrors;
                }
                
                public static function isValidCourse($course)
                {
                    $errors = Person::validateCourse($course);

                    if (sizeof($errors) > 0)
                    {
                        return false;
                    }
                    
                    return true;
                }
		
	}
}

?>
