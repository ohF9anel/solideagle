<?php


namespace solideagle\data_access;

use solideagle\data_access\database\DatabaseCommand;
class Type
{
	
	//these one should be the same as the database
	const TYPE_ADMIN = 1;
	const TYPE_LEERKRACHT = 2;
	const TYPE_LEERLING =3;
	const TYPE_STAGIAR =4;
	const TYPE_OUDER =5;
	
	// variables
	private $id;
	private $typeName;

	// getters & setters

	public function getId()
	{
		return $this->id;
	}

	public function setId($id)
	{
		$this->id = $id;
	}

	public function getTypeName()
	{
		return $this->typeName;
	}

	public function setTypeName($typeName)
	{
		$this->typeName = $typeName;
	}

	public function __construct($id, $typeName = "")
	{
		$this->id = $id;
		$this->typeName = $typeName;
	}

	// manage types

	public static function addType($type)
	{
		$sql = "INSERT INTO  `type`
		(
		`id`,
		`type_name`
		)
		VALUES
		(
		:id,
		:type_name
		);";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":id", $type->getId());
		$cmd->addParam(":type_name", $type->getTypeName());

		$cmd->BeginTransaction();

		$cmd->execute();

		$cmd->newQuery("SELECT LAST_INSERT_ID();");

		$retval = $cmd->executeScalar();

		$cmd->CommitTransaction();
		return $retval;
	}

	public static function delTypeById($typeId)
	{
		$sql = "DELETE FROM  `type`
		WHERE `id` = :id;";

		$cmd = new DatabaseCommand($sql);
		$cmd->addParam(":id", $typeId);

		$cmd->execute();
	}

	public static function getAll()
	{
		$sql = "SELECT
		`type`.`id`,
		`type`.`type_name`
		FROM  `type`;";
		 
		$cmd = new DatabaseCommand($sql);
		 
		$retarr = array();
		 
		$cmd ->executeReader()->readAll(function($rowdata) use (&$retarr){

			$retarr[] = new Type($rowdata->id, $rowdata->type_name);


		});
		 
		 
		 
		 
		return $retarr;
	}

}



?>