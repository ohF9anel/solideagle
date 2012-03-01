<?php
namespace Database;

require_once("database.php");
require_once("customdatareader.php");

 class DatabaseCommand
 {
 	
 	private static $_defaultConnectionParams;
 	
 	private $_sql; //string
 	private $_params = array(); //array
 	private $_connectionParams; //array
 	
 	public function __construct($connectionParams,$sql)
 	{
 		$this->_connectionParams = $connectionParams;
 		$this->_sql = $sql;
 	}
 	
 	public function __construct($sql)
 	{
 			__construct($_defaultConnectionParams,$sql);
 	}

 	public function get_sql()
 	{
 		return $this->_sql;
 	}
 	
 	public function has_params()
 	{
 		return count($this->_params) > 0?true:false;
 	}
 	
 	public function get_params()
 	{
 		return $this->_params;
 	}
 	
 	public function get_connectionParams()
 	{
 		return $this->_connectionParams;
 	}
 	
 	public function addParam($paramName,$value)
 	{
 		$_params[$paramName] = $value;
 	}
 	
 	public function executeReader()
 	{
 		return Database::executeReader($this);
 	}
 	
 	public function executeScalar()
 	{
 		return Database::executeScalar($this);
 	} 	
 	
 	public function execute()
 	{
 		return Database::execute($this);
 	}
 	
 }














?>