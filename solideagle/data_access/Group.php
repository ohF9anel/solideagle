<?php

namespace DataAccess;

require_once '../data_access/database/databasecommand.php';

use Database\DatabaseCommand;

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
	 * 
	 * Enter description here ...
	 * @param Group $group
	 * @param DatabaseCommand $cmd
	 */
	private static function addGroupRecursive($group,$cmd)
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
		

	echo $group->id;
		
		foreach ($group->getChildGroups() as $childgrp)
		{
			$childgrp->setParentId($group->getId());
			
			Group::addGroupRecursive($childgrp,$cmd);
		}
	}


	public static function updateGroup($group, $parentId)
	{

	}

	public static function delGroupById($groupId)
	{

	}



}


?>