<?php

namespace solideagle\data_access;

	use solideagle\data_access\database\DatabaseCommand;

    class RelationType
    {

        // variables
        private $id;
        private $description;

        // getters & setters

        public function getId()
        {
            return $this->id;
        }

        public function setId($id)
        {
            $this->id = $id;
        }

        public function getDescription()
        {
            return $this->description;
        }

        public function setDescription($description)
        {
            $this->description = $description;
        }

    }
    


?>
