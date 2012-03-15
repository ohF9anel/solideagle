<?php

namespace DataAccess;



require_once 'database/databasecommand.php';
require_once 'validation/Validator.php';
require_once 'logging/Logger.php';

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

	public function addChildGroups($arrChildGroup)
	{
		$this->childGroups = array_merge($this->childGroups,$arrChildGroup);
	}

	public function getChildGroups()
	{
		return $this->childGroups;
	}

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
        
        public function __construct($id="", $name="")
        {
                $this->id = $id;
                $this->name = $name;
        }

	// manage groups

	/**
	 *
	 * Adds a new group under its parent,set by parentid, or as root if parentid is not set.
	 * Will also save the childgroups
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


			$cmd->RollbackTransaction();

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


			Group::addGroupRecursive($childgrp,$cmd);
		}
	}


	/**
	 *
	 * Only updates group name and group description
	 * @param Group $group
	 */
	public static function updateGroup($group)
	{
		$err = Group::validateGroup($group);
		if(!empty($err))
		{
			assert("false /* Group not validated before updating! See log for details*/");

			Logger::getLogger()->log("Group not validated before updating! Validation errors:\n" . var_export($err,true) . "\nObject dump:\n" . var_export($group,true) . "\n",PEAR_LOG_ERR);
				
			return false;
		}

		$sql = "UPDATE `CentralAccountDB`.`group`
				SET
				`name` = :name,
				`description` = :description
				WHERE `id` = :id;";

		$cmd = new DatabaseCommand();
		$cmd->newQuery($sql);
			
		$cmd->addParam(":name", $group->getName());
		$cmd->addParam(":description", $group->getName());
		$cmd->addParam(":id", $group->getId());
			
		$cmd->execute();


	}

	/**
	 *
	 * moves a group to his parentid
	 * @param Group $group
	 */
	public static function moveGroup($group)
	{
		$sql = "DELETE a FROM group_closure AS a
				JOIN group_closure AS d ON a.child_id = d.child_id
				LEFT JOIN group_closure AS x
				ON x.parent_id = d.parent_id AND x.child_id = a.parent_id
				WHERE d.parent_id = :id AND x.parent_id IS NULL;";

		$cmd = new DatabaseCommand($sql);
		$cmd->BeginTransaction();
		$cmd->addParam(":id", $group->getId());
		$cmd->execute();


		$sql = "INSERT INTO group_closure (parent_id, child_id, length)
				(SELECT supertree.parent_id, subtree.child_id,
				supertree.length+subtree.length+1
				FROM group_closure AS supertree JOIN group_closure AS subtree
				WHERE subtree.parent_id = :id 
				AND supertree.child_id = :newparentid);";

		$cmd->newQuery($sql);
		$cmd->addParam(":id", $group->getId());
		$cmd->addParam(":newparentid", $group->getParentId());
		$cmd->execute();

		$cmd->CommitTransaction();
	}

	public static function getRoots()
	{
		$sql = "SELECT
				`group`.`id`,
				`group`.`name`,
				`group`.`description`
				FROM `CentralAccountDB`.`group`, group_closure AS c 
                LEFT OUTER JOIN group_closure AS anc
				ON anc.child_id = c.child_id AND anc.parent_id <> c.parent_id
				WHERE anc.parent_id IS NULL and  `group`.`id` = c.parent_id";

		$retArr = array();

		$cmd = new DatabaseCommand($sql);
		$cmd->executeReader()->readAll(function($row) use (&$retArr) {

			$tempGroup = new Group();
			$tempGroup->setId($row->id);
			$tempGroup->setName($row->name);
			$tempGroup->setDescription($row->description);

			$retArr[] = $tempGroup;

		});
			
		return $retArr;
	}

	/**
	 * 
	 * Enter description here ...
	 * @param Group $group
	 * @return multitype:\DataAccess\Group
	 */
	public static function getChilderen($group)
	{
		$sql = "select
		    g.id, g.name, g.description
		from
		    `group` as g,
		    group_closure as c
		where
		    c.child_id = g.id and c.parent_id = :parentid and length = 1";

		$retArr = array();

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":parentid", $group->getId());
		$cmd->executeReader()->readAll(function($row) use (&$retArr,$group) {

			$tempGroup = new Group();
			$tempGroup->setParentId($group->getParentId());
			$tempGroup->setId($row->id);
			$tempGroup->setName($row->name);
			$tempGroup->setDescription($row->description);

			$retArr[] = $tempGroup;

		});
			
		return $retArr;
	}

	public static function getTreeSLOW()
	{
		return Group::getTreeRecursive(Group::getRoots());
	}
	
	public static function getTree()
	{
		$sql = "SELECT p.id,p.name,p.description,t.length, 
		(SELECT t1.parent_id FROM group_closure t1 WHERE t1.length=1 AND t1.child_id=t.child_id) AS parent
		FROM `group` p JOIN group_closure t ON p.id=t.child_id  order by t.length,parent";
		
		$rootArr = array();
		$completeArr = array();
		
		$cmd = new DatabaseCommand($sql);
		$cmd->executeReader()->readAll(function($row) use (&$rootArr,&$completeArr) {
			
			$childGroup = new Group();
			$childGroup->setId($row->id);
			$childGroup->setName($row->name);
			$childGroup->setDescription($row->description);
			$childGroup->setParentId($row->parent);
			
			$completeArr[$row->id] = $childGroup;
	
			if($row->parent == NULL)
			{
				$rootArr[] = $childGroup;	
			}
				
		});
		
		//order groups
		foreach($completeArr as $tempgroup)
		{
			if(($parentId = $tempgroup->getParentId()) != NULL)
			{
				$completeArr[$parentId]->addChildGroup($tempgroup);
			}
		}
		
		return $rootArr;
	}

	private static function getTreeRecursive($groups)
	{
		foreach($groups as &$root)
		{
			$childeren = Group::getChilderen($root);
			$root->addChildGroups($childeren);
			Group::getTreeRecursive($childeren);
		}
		
		return $groups;
	}



	public static function delGroupById($groupId)
	{
		//do not delete if group has members or subgroups!!!??!
		
		$sql = "SET SQL_SAFE_UPDATES=0;";
		
		$cmd = new DatabaseCommand($sql);
		$cmd->BeginTransaction();
		$cmd->execute();

		$sql = "DELETE gc FROM `CentralAccountDB`.`group_closure` as gc
			WHERE (gc.parent_id = :groupid OR gc.child_id = :groupid);";
		
		$cmd->newQuery($sql);
		$cmd->addParam(":groupid", $groupId);
		$cmd->execute();
		
		$sql = "DELETE g FROM  `CentralAccountDB`.`group` as g
			WHERE g.id = :groupid;";
		
		$cmd->newQuery($sql);
		$cmd->addParam(":groupid", $groupId);
		$cmd->execute();

		$cmd->CommitTransaction();

	}
	
	/**
	 * returns parents in array with (depth,group) ordered by depth
	 * 
	 * @param Group $group
	 */
	public static function getParents($group)
	{
		$sql = "SELECT p.`id`,
				p.`name`,
				p.`description`, t.length FROM `group` p 
				JOIN group_closure t ON p.id=t.parent_id 
				WHERE t.child_id =  :groupid 
				AND t.child_id <> t.parent_id
				ORDER BY t.length;";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":groupid", $group->getId());
		
		$retArr = array();
		
		$cmd->executeReader()->readAll(function($row) use (&$retArr){
				
			$tmpgroup = new Group();
			$tmpgroup->setId($row->id);
			$tmpgroup->setName($row->name);
			$tmpgroup->setDescription($row->description);
				
			$retArr[] = $tmpgroup;
				
		});
		
		return $retArr;
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
		
		foreach(Validator::validateInt($group->getParentId()) as $valError)
		{
			if($valError == \Validation\ValidationError::NO_NUMBER)
			{
				$validationErrors[] = "Parentid moet een nummer zijn!";
			}
		}


		return $validationErrors;


	}

	
	public static function getGroupById($groupid)
	{
		$sql = "SELECT p.`id`,
		p.`name`,
		p.`description`FROM `group` p WHERE  p.`id` = :groupid";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":groupid", $groupid);
		
		$tmpgroup = NULL;
		
		if($row = $cmd->executeReader()->read())
		{
			
			$tmpgroup = new Group();
			$tmpgroup->setId($row->id);
			$tmpgroup->setName($row->name);
			$tmpgroup->setDescription($row->description);
		}

		return $tmpgroup;
	
	}



}


/*select group_concat(n.name order by a.length desc separator ' -> ') as path
 from group_closure d
join group_closure a on (a.child_id = d.child_id)
join `group` n on (n.id = a.parent_id)
where d.parent_id in
(select parent_id  from group_closure tc  where
not exists (
select null    from group_closure tci    where tc.child_id = tci.child_id      and tci.length <> 0  ))
and d.child_id != d.parent_id
group by d.child_id */

?>