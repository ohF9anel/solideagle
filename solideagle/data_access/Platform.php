<?php

namespace DataAccess
{

    class Platform
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

        // manage platforms

        public static function addPlatform($platform)
        {
                $sql = "INSERT INTO `CentralAccountDB`.`platform`
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
                $cmd->addParam(":id", $platform->getName());
                $cmd->addParam(":name", $platform->getName());

                $cmd->BeginTransaction();

                $cmd->execute();

                $cmd->newQuery("SELECT LAST_INSERT_ID();");

                $retval =  $cmd->executeScalar();

                $cmd->CommitTransaction();
                return $retval;
        }

        public static function updatePlatform($platform)
        {

        }

        public static function delPlatformById($platformId)
        {
                $sql = "DELETE FROM `CentralAccountDB`.`platform`
                                WHERE `id` = :id;";

                $cmd = new DatabaseCommand($sql);
                $cmd->addParam(":id", $platformId);

                $cmd->execute();
        }

    }
    
}

?>
