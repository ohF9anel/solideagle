<?php
namespace Database;

use PDO;

class CustomDataReader
{
	
	private $_stmt;
	
	public function __construct($stmt)
	{
		$this->_stmt = $stmt;
	}
	
	public function read() //false on failure
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