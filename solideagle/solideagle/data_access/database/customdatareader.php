<?php
namespace solideagle\data_access\database;

use PDO;

class CustomDataReader
{
	
	private $_stmt;
	
	public function __construct($stmt)
	{
		$this->_stmt = $stmt;
	}
	
	/*
	 * returns one record
	 * return false on no more records
	 * 
	 */
	public function read() 
	{
		return $this->_stmt->fetch(PDO::FETCH_OBJ);
	}	
	
	public function readAll($callback)
	{
		while($row = $this->read())
		{
			call_user_func($callback,$row);
		}
	}
}


?>