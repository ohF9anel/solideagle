<?php



namespace solideagle\data_access;

use solideagle\data_access\helpers\UnicodeHelper;

use solideagle\data_access\database\DatabaseCommand;
use solideagle\data_access\validation\Validator;
use solideagle\data_access\validation\ValidationError;
use solideagle\logging\Logger;
use solideagle\data_access\Type;

class Group
{

	// variables
	private $id;
	private $name;
	private $description;
	private $childGroups = array();
	private $parentId = NULL;
	private $types = array();
	private $amountOfmembers;
	private $totalAmountOfMembers;
	private $uniquename;
	
	private $instituteNumber;
	private $administrativeNumber;

	// getters, setters & functions

	public function addChildGroup($childGroup)
	{
		$this->childGroups[] = $childGroup;
	}

	public function addChildGroups($arrChildGroup)
	{
		$this->childGroups = array_merge($this->childGroups,$arrChildGroup);
	}

	//not always populated!
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

	//ugly hacks!
	public function setName($name,$external=true)
	{
		$this->name = $name;
		if($external)
		{
			$this->uniquename = UnicodeHelper::cleanEmailString($name);
		}
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
	
	
	public static function isMemberOf($childgroupid,$parentgroupid)
	{
		$sql = "SELECT count(*) as memberof FROM group_closure gc WHERE gc.child_id = :childgroupid AND gc.parent_id = :parentgroupid AND gc.child_id != gc.parent_id";
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":childgroupid", $childgroupid);
		$cmd->addParam(":parentgroupid", $parentgroupid);
		
		$count = $cmd->executeReader()->read()->memberof;
		
		return ($count > 0);
		
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

		$id = Group::addGroupRecursive($group,$cmd);
		
		if($id === false)
		{
			return NULL;
		}

		$cmd->CommitTransaction();

		return $id;
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
			echo("Group not validated before saving! See log for details");

			Logger::log("Group not validated before saving! Validation errors:\n" . var_export($err,true) . "\nObject dump:\n" . var_export($group,true) . "\n",PEAR_LOG_ERR);

			$cmd->RollbackTransaction();

			return false;
		}


		$sql = "INSERT INTO  `group`
		(
		`name`,
		`description`,
		`uniquename`,
		`instituteNumber`,
		`administrativeNumber`)
		VALUES
		(
		:name,
		:desc,
		:uniquename,
		:instnr,
		:adminnr
		);";
			
		$cmd->newQuery($sql);

		$cmd->addParam(":name", $group->getName());
		$cmd->addParam(":desc", $group->getDescription());
		$cmd->addParam(":instnr", $group->getInstituteNumber());
		$cmd->addParam(":adminnr", $group->getAdministrativeNumber());
		$cmd->addParam(":uniquename", $group->getUniquename());
		
			
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


		$sql = "INSERT INTO  `default_type_group`
		(`type`,
		`group`)
		VALUES
		(
		:type,
		:group
		);";

		foreach($group->types as $type)
		{
			$cmd->newQuery($sql);
				
			$cmd->addParam(":group", $group->getId());
			$cmd->addParam(":type", $type->getId());
				
			$cmd->execute();
		}

		foreach ($group->getChildGroups() as $childgrp)
		{
			$childgrp->setParentId($group->getId());

			if(Group::addGroupRecursive($childgrp,$cmd) === false)
			{
				return false;
			}
		}

		return $group->id;
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

			Logger::log("Group not validated before updating! Validation errors:\n" . var_export($err,true) . "\nObject dump:\n" . var_export($group,true) . "\n",PEAR_LOG_ERR);

			return false;
		}

		$sql = "UPDATE  `group`
		SET
		`name` = :name,
		`uniquename` = :uniquename,
		`description` = :description
		WHERE `id` = :id;";

		$cmd = new DatabaseCommand();
		$cmd->newQuery($sql);
			
		$cmd->addParam(":name", $group->getName());
		$cmd->addParam(":uniquename", $group->getUniquename());
		$cmd->addParam(":description", $group->getDescription());
		$cmd->addParam(":id", $group->getId());
			
		$cmd->execute();

		//remove types
		$sql = "DELETE FROM  `default_type_group`
		WHERE `group` = :groupid; ";

		$cmd->newQuery($sql);
		$cmd->addParam(":groupid", $group->getId());
		$cmd->execute();

		//reinsert types
		$sql = "INSERT INTO  `default_type_group`
		(`type`,
		`group`)
		VALUES
		(
		:type,
		:group
		);";

		foreach($group->types as $type)
		{
			$cmd->newQuery($sql);

			$cmd->addParam(":group", $group->getId());
			$cmd->addParam(":type", $type->getId());

			$cmd->execute();
		}
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
		`group`.uniquename,
		`group`.`description`
		FROM  `group`, group_closure AS c
		LEFT OUTER JOIN group_closure AS anc
		ON anc.child_id = c.child_id AND anc.parent_id <> c.parent_id
		WHERE anc.parent_id IS NULL and  `group`.`id` = c.parent_id";

		$retArr = array();

		$cmd = new DatabaseCommand($sql);
		$cmd->executeReader()->readAll(function($row) use (&$retArr) {

			$tempGroup = new Group();
			$tempGroup->setId($row->id);
			$tempGroup->setName($row->name,false);
			$tempGroup->setUniquename($row->uniquename);
			$tempGroup->setDescription($row->description);

			$retArr[] = $tempGroup;

		});
			
		return $retArr;
	}

	/**
	 *
	 * gets childeren just below this group
	 * @param Group $group
	 * @return multitype:\DataAccess\Group
	 */
	public static function getChilderen($group)
	{
		$sql = "select
		g.id, g.name, g.uniquename, g.description
		from
		`group` as g,
		group_closure as c
		where
		c.child_id = g.id and g.deleted = 0 and c.parent_id = :parentid and length = 1";

		$retArr = array();

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":parentid", $group->getId());
		$cmd->executeReader()->readAll(function($row) use (&$retArr,$group) {

			$tempGroup = new Group();
			$tempGroup->setParentId($group->getParentId());
			$tempGroup->setId($row->id);
			$tempGroup->setName($row->name,false);
			$tempGroup->setUniquename($row->uniquename);
			$tempGroup->setDescription($row->description);

			$retArr[] = $tempGroup;

		});
			
		return $retArr;
	}

	/**
	 *
	 * gets all childeren
	 * @param Group $group
	 * @return multitype:\DataAccess\Group
	 */
	public static function getAllChilderen($group)
	{
		$sql = "select
		g.id, g.name,g.uniquename, g.description
		from
		`group` as g,
		group_closure as c
		where
		c.child_id = g.id and g.deleted = 0 and c.parent_id = :parentid";
	
		$retArr = array();
	
		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":parentid", $group->getId());
		$cmd->executeReader()->readAll(function($row) use (&$retArr,$group) {
	
			$tempGroup = new Group();
			$tempGroup->setParentId($group->getParentId());
			$tempGroup->setId($row->id);
			$tempGroup->setName($row->name,false);
			$tempGroup->setUniquename($row->uniquename);
			$tempGroup->setDescription($row->description);
	
			$retArr[] = $tempGroup;
	
		});
			
		return $retArr;
	}
	
	//do not use, it is too slow.
	public static function getTreeSLOW()
	{
		return Group::getTreeRecursive(Group::getRoots());
	}

	public static function getAllGroups()
	{
		$sql = "SELECT p.id,p.name,p.uniquename,p.description,t.length,
		(SELECT t1.parent_id FROM group_closure t1 WHERE t1.length=1 AND t1.child_id=t.child_id) AS parent
		FROM `group` p JOIN group_closure t ON p.id=t.child_id  WHERE p.deleted = 0  order by t.length,parent";


		$completeArr = array();

		$cmd = new DatabaseCommand($sql);
		$cmd->executeReader()->readAll(function($row) use (&$completeArr) {

			$childGroup = new Group();
			$childGroup->setId($row->id);
			$childGroup->setName($row->name,false);
			$childGroup->setUniquename($row->uniquename);
			$childGroup->setDescription($row->description);
			$childGroup->setParentId($row->parent);

			$completeArr[$row->id] = $childGroup;

				
		});



		return $completeArr;


	}

	public static function getTree()
	{


		$cmd = new DatabaseCommand();

		//I hope you like sql.

		//this query selects just the tree and not the amount of members in each group

		/*$sql = "SELECT p.id,p.name,p.description,t.length,
		 (SELECT t1.parent_id FROM group_closure t1 WHERE t1.length=1 AND t1.child_id=t.child_id) AS parent
		FROM `group` p JOIN group_closure t ON p.id=t.child_id  WHERE p.deleted = 0 group by p.id order by p.name";/* you can also order by p.id*/


		//this query selects the tree and the amount of members from each group

		/*$sql ="SELECT groups.id,groups.name,groups.description,groups.length,groups.parent,count(pn.id) FROM
		 (
		 		SELECT p.id ,p.name,p.description,t.length,
		 		(SELECT t1.parent_id FROM group_closure t1 WHERE t1.length=1 AND t1.child_id=t.child_id ) AS parent
		 		FROM `group` p
		 		JOIN group_closure t ON p.id=t.child_id
		 		WHERE p.deleted = 0 group by p.id order by p.name) as groups

		LEFT JOIN person as pn ON pn.group_id = groups.id group by groups.id";*/

		//this query selects the tree and the amount of members from each group
		//and is benchmarked to be a little faster

		$sql ="SELECT p.id ,p.name,p.uniquename,p.description,t.length,count(pn.id) as amountofmembers,
		(SELECT t1.parent_id FROM group_closure t1 WHERE t1.length=1 AND t1.child_id=t.child_id ) AS parent
		FROM `group` p
		JOIN (SELECT child_id, length from group_closure group by child_id) t ON p.id=t.child_id
		LEFT JOIN allPersons as pn ON pn.group_id = p.id
		WHERE p.deleted = 0 group by p.id order by p.name";

		$rootArr = array();
		$completeArr = array();

		$cmd->newQuery($sql);
		$cmd->executeReader()->readAll(function($row) use (&$rootArr,&$completeArr) {
				
			$childGroup = new Group();
			$childGroup->setId($row->id);
			$childGroup->setName($row->name,false);
			$childGroup->setUniquename($row->uniquename);
			$childGroup->setDescription($row->description);
			$childGroup->setParentId($row->parent);
			$childGroup->setAmountOfmembers($row->amountofmembers);
			$childGroup->setTotalAmountOfMembers($row->amountofmembers);
				
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

		foreach($rootArr as &$group)
		{
			self::sumAmountOfMembers($group);
		}

		return $rootArr;
	}

	private static function sumAmountOfMembers($group)
	{
		$ret = 0;
		foreach($group->getChildGroups() as $childgroup)
		{
			self::sumAmountOfMembers($childgroup);
			$ret += $childgroup->getTotalAmountOfmembers();
		}
		$group->addAmountOfMembers($ret);
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
		$sql = "UPDATE  `group`
		SET
		`deleted` = 1
		WHERE `id` = :groupid;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":groupid", $groupId);
		$cmd->BeginTransaction();
		$cmd->execute();
		$cmd->CommitTransaction();
	}


	public static function reallyDelGroupById($groupId)
	{
		//do not delete if group has members or subgroups!!!??!

		$sql = "SET SQL_SAFE_UPDATES=0;";

		$cmd = new DatabaseCommand($sql);
		$cmd->BeginTransaction();
		$cmd->execute();

		$sql = "DELETE gc FROM  `group_closure` as gc
		WHERE (gc.parent_id = :groupid OR gc.child_id = :groupid);";

		$cmd->newQuery($sql);
		$cmd->addParam(":groupid", $groupId);
		$cmd->execute();

		$sql = "DELETE g FROM   `group` as g
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
		p.`name`,p.uniquename,
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
			$tmpgroup->setName($row->name,false);
			$tmpgroup->setUniquename($row->uniquename);
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

		//parentId can be NULL
		if($group->getParentId() !== NULL)
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
		p.`administrativeNumber`,
		p.uniquename,
		p.`instituteNumber`,
		p.`description`
		FROM `group` p WHERE  p.`id` = :groupid";

		$cmd = new DatabaseCommand($sql);

		$cmd->addParam(":groupid", $groupid);

		$tmpgroup = NULL;

		if($row = $cmd->executeReader()->read())
		{
			$tmpgroup = new Group();
			$tmpgroup->setId($row->id);
			$tmpgroup->setName($row->name,false);
			$tmpgroup->setUniquename($row->uniquename);
			$tmpgroup->setDescription($row->description);
			$tmpgroup->setAdministrativeNumber($row->administrativeNumber);
			$tmpgroup->setInstituteNumber($row->instituteNumber);
		}



		return $tmpgroup;

	}
	
	
	public static function getGroupByUniqueName($uniquename,$excludedId=NULL)
	{
		$sql = "SELECT p.`id`
		FROM `group` p WHERE  p.`uniquename` = :uniquename and p.`id` != :excludedId";
		
		
		
		$cmd = new DatabaseCommand($sql);
		
		$cmd->addParam(":uniquename", $uniquename);
		$cmd->addParam(":excludedId", $excludedId);
		
		$row = $cmd->executeReader()->read();
		
		if($row)
		{
			return self::getGroupById($row->id);
		}
		return NULL;
	}

	public static function getGroupByName($groupname)
	{
		$sql = "SELECT p.`id`
		FROM `group` p WHERE  p.`name` = :groupname";

		$cmd = new DatabaseCommand($sql);

		$cmd->addParam(":groupname", $groupname);

		$row = $cmd->executeReader()->read();
		
		if($row)
		{
			return self::getGroupById($row->id);
		}
		return NULL;
	}


	public function getTypes()
	{
		$sql = "SELECT 		`type`.`id`, `type`.`type_name`

		FROM  `default_type_group`,  `type`
		WHERE `default_type_group`.`type` = `type`.`id` AND `default_type_group`.`group` = :groupid;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":groupid", $this->getId());

		$types=array();

		$cmd->executeReader()->readAll(function($dataobj) use (&$types)
		{
			$types[] = new Type($dataobj->id,$dataobj->type_name);
		});

		return $types;
	}

	public function addType($type)
	{
		$this->types[] = $type;
	}

	public static function doesGroupExistByName($groupName)
	{
		$sql = "SELECT count(*) as groupcount FROM `group` WHERE `name` = :groupName AND `deleted` = 0";

		$cmd = new DatabaseCommand($sql);

		$cmd->addParam(":groupName", $groupName);

		if($row = $cmd->executeReader()->read())
		{
			return ($row->groupcount > 0);
		}
	}

	public function getAmountOfmembers()
	{
		return $this->amountOfmembers;
	}

	public function setAmountOfmembers($amountOfmembers)
	{
		$this->amountOfmembers = $amountOfmembers;
	}

	public function addAmountOfMembers($amountOfmembers)
	{
		$this->totalAmountOfMembers += $amountOfmembers;
	}

	public function getTotalAmountOfMembers()
	{
	    return $this->totalAmountOfMembers;
	}

	public function setTotalAmountOfMembers($totalAmountOfMembers)
	{
	    $this->totalAmountOfMembers = $totalAmountOfMembers;
	}
	
	public function getInstituteNumber()
	{
	    return $this->instituteNumber;
	}

	public function setInstituteNumber($instituteNumber)
	{
	    $this->instituteNumber = $instituteNumber;
	}

	public function getAdministrativeNumber()
	{
	    return $this->administrativeNumber;
	}

	public function setAdministrativeNumber($administrativeNumber)
	{
	    $this->administrativeNumber = $administrativeNumber;
	}

	public function getUniquename()
	{
	    return $this->uniquename;
	}

	public function setUniquename($uniquename)
	{
	    $this->uniquename = $uniquename;
	}
	
	public static function getMailAdd($group)
	{
		if(Group::isMemberOf($group->getId(),Group::getGroupByName("leerlingen")->getId()))
		{
			return $group->getUniquename() . "@" . \solideagle\Config::singleton()->googledomainstudent;
		}else{
			return $group->getUniquename() . "@" . \solideagle\Config::singleton()->googledomain;
		}
	}
}




?>