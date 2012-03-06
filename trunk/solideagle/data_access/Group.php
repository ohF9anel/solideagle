<?php

namespace DataAccess;

require_once 'database/databasecommand.php';
require_once 'validation/Validator.php';
require_once '../logging/Logger.php';

use Database\DatabaseCommand;
use validation\Validator;
use validation\ValidationError;
use Logging\Logger;

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
		$this->childGroups[] = $childGroup;
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
	 * Adds a new group under its parent (set by parentid) or as root if parentid is not set.
	 * Will also save the childgroups
	 *
	 *
	 * @param Group $group
	 * @return int
	 */
	public static function addGroup($group)
	{

		$cmd = new DatabaseCommand();

		$cmd->BeginTransaction();

		Group::addGroupRecursive($group,$cmd);

		$cmd->CommitTransaction();
	}

	/**
	 * gets called by addGroup();
	 *
	 * @param Group $group
	 * @param DatabaseCommand $cmd
	 */
	private static function addGroupRecursive($group,$cmd)
	{
		$err = Group::validateGroup($group);
		if(!empty($err))
		{
			assert("false /* Group not validated before saving! See log for details*/");
			
			Logger::getLogger()->log("Group not validated before saving! Validation errors:\n" . var_export($err,true) . "\nObject dump:\n" . var_export($group,true) . "\n",PEAR_LOG_ERR);
			
			return false;
		}
		

		$sql = "INSERT INTO `CentralAccountDB`.`group`
        						(
        						`name`,
        						`description`)
        						VALUES
        						(
        						:name,
        						:desc
        						);";
			
		$cmd->newQuery($sql);

		$cmd->addParam(":name", $group->getName());
		$cmd->addParam(":desc", $group->getDescription());
			
		$cmd->execute();

		$cmd->newQuery("SELECT LAST_INSERT_ID();");
			
		$group->id =  $cmd->executeScalar();

		$sql = "INSERT INTO group_closure (parent_id, child_id, length)
        							SELECT t.parent_id, :groupid, t.length+1
        							FROM group_closure AS t
        							WHERE t.child_id = :parentid
        							UNION ALL
        							SELECT :groupid, :groupid, 0;";

		$cmd->newQuery($sql);
			
		$cmd->addParam(":groupid", $group->getId());
		$cmd->addParam(":parentid", $group->getParentId());

		$cmd->execute();


		foreach ($group->getChildGroups() as $childgrp)
		{
			$childgrp->setParentId($group->getId());
				
			if(!isValidGroup($childgrp))
			{
				$cmd->RollbackTransaction();
				return false;
			}
				
			Group::addGroupRecursive($childgrp,$cmd);
		}
	}


	public static function updateGroup($group, $parentId)
	{

	}

	public static function delGroupById($groupId)
	{

	}

	/**
	 * Validates a Group object, returns array with validation errors
	 *
	 * @param Group $group
	 */
	public static function validateGroup($group)
	{

		$validationErrors = array();
		

		foreach (Validator::validateString($group->getName(),1,45,false) as $valError)
		{
			if($valError == ValidationError::STRING_TOO_LONG )
			{
				$validationErrors[] = "De naam van de groep mag niet langer zijn dan 45 karakters.";
			}
			elseif($valError == ValidationError::STRING_TOO_SHORT || $valError == ValidationError::IS_NULL)
			{
				$validationErrors[] = "Groep moet een naam hebben.";
			}
			elseif($valError == ValidationError::STRING_HAS_SPECIAL_CHARS)
			{
				$validationErrors[] = "Groep naam mag geen speciale tekens bevatten";
			}
		}
		
		
		return $validationErrors;


	}




}


?>