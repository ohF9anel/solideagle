<?php
namespace solideagle\data_access\database;


class DatabaseCommand
{

	private static $_defaultConnectionParams = array('mysql:host=10.3.7.102;dbname=CentralAccountDB;charset=utf8',"dbuser","ChaCha69");

	private $_isTransacion = false;
	private $_sql; //string
	private $_params = array(); //array
	private $_connectionParams; //array

	public function __construct($sql = NULL,$connectionParams = NULL)
	{
		if($connectionParams === NULL)
		{
			$this->_connectionParams = DatabaseCommand::$_defaultConnectionParams;
		}else{
			$this->_connectionParams = $connectionParams;
		}
			
		$this->newQuery($sql);
	}



	public function getSQL()
	{
		return $this->_sql;
	}
	
	public function newQuery($sql)
	{
		$this->_sql = $sql;
		$this->_params = array();
	}

	public function hasParams()
	{
		return count($this->_params) > 0?true:false;
	}

	public function getparams()
	{
		return $this->_params;
	}

	public function getconnectionParams()
	{
		return $this->_connectionParams;
	}

	public function addParam($paramName,$value)
	{
		$this->_params[$paramName] = $value;
	}

	public function executeReader()
	{
		return Database::executeReader($this);
	}

	public function executeScalar()
	{
		return Database::executeScalar($this);
	}

	/**
	 * 
	 * Executes SQL, returns true on success
	 * 
	 * @return bool
	 */
	public function execute()
	{
		return Database::execute($this);
	}

	public function BeginTransaction()
	{
		Database::BeginTransaction($this->_connectionParams );
		$this->_isTransacion = true;
	}


	public  function CommitTransaction()
	{
		Database::CommitTransaction($this->_connectionParams );
		$this->_isTransacion = false;
	}

	public  function RollbackTransaction()
	{
		Database::RollbackTransaction($this->_connectionParams );
		$this->_isTransacion = false;
	}



}














?>