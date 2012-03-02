<?php
	
namespace DataAccess;

    class Group
    {

        // variables
        private $id;
        private $name;
        private $description;
        private $childGroups = array();
        private $groupTypes = array();
        private $parentId = NULL;
        

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
        
        public function setDescription($desc)
        {
        	$this->description = $desc;
        }
        
        public function getDescription()
        {
        	return $this->description;
        }
        
        public function getParentId()
        {
        	return $this->parentId;
        }
        
        public function setParentId($parentId)
        {
        	$this->parentId = $parentId;
        }


        // manage groups

        /**
         * 
         * 
         * @param Group $group
         * @return int
         */
        public static function addGroup($group)
        {
			$sql = "INSERT INTO `CentralAccountDB`.`group`
					(
					`name`,
					`description`)
					VALUES
					(
					:name,
					:desc
					);";
			
			
			$cmd = new DatabaseCommand($sql);
			$cmd->addParam(":name", $group->getName());
			$cmd->addParam(":desc", $group->getDescription());
			
			$cmd->BeginTransaction();
			
			$cmd->execute();
			
			$cmd->newQuery("SELECT LAST_INSERT_ID();");
			
			$retval =  $cmd->executeScalar();
			
			$cmd->CommitTransaction();
			return $retval;
					
        }

        public static function updateGroup($group, $parentId)
        {

        }

        public static function delGroupById($groupId)
        {

        }

    
     	
}


?>