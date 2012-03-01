<?php
	
namespace DataAccess
{
    
    class GroupType
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

        // manage group types

        public static function addGroupType($groupType)
        {

        }

        public static function updateGroupType($groupType)
        {

        }

        public static function removeGroupTypeById($groupTypeId)
        {

        }

    }

    class Group
    {

        // variables
        private $id;
        private $name;
        private $descriptionRights;
        private $childGroups = array();

        // getters, setters & functions

        public function addChildGroup($childGroup)
        {
                $this->children[] = $childGroup;
        }

        public function getChildGroups()
        {
                return $this->childGroups;
        }	

        public function getId()
        {
            return $this->id;
        }

        public function getName()
        {
            return $this->name;
        }

        public function setName($name)
        {
            $this->name = $name;
        }

        public function getDescriptionRights()
        {
            return $this->descriptionRights;
        }

        public function setDescriptionRights($descriptionRights)
        {
            $this->descriptionRights = $descriptionRights;
        }

        // manage groups

        public static function addGroup($group, $parentId)
        {

        }

        public static function updateGroup($group, $parentId)
        {

        }

        public static function delGroupById($groupId)
        {

        }

    }

}

?>