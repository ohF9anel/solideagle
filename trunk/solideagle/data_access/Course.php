<?php

namespace DataAccess
{
    
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

        // manage courses

        public static function addCourse($course)
        {

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
