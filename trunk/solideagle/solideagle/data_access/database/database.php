<?php

namespace solideagle\data_access\database;

use PDO;

class DBconnection
{
	/**
	 *
	 * Enter description here ...
	 * @var PDO
	 */
	public $connection;

	/**
	 *
	 * Enter description here ...
	 * @var boolean
	 */
	public $isTransactionInProgress;


	/**
	 *
	 * Enter description here ...
	 * @param PDO $connection
	 */
	public function __construct($connection)
	{
		$this->connection = $connection;
		$this->isTransactionInProgress = false;
	}
}

class Database
{

	/**
	 * @var array(DBconnection)
	 */
	private static $_connections = array();


	/**
	 * Executes SQL and returns first value in first row
	 *
	 *$connectionParams[0] = DSN
	 *$connectionParams[1] = username
	 *$connectionParams[2] = password
	 *
	 * @param array $connectionParams
	 * @return DBconnection
	 */

	public static function getConnection($connectionParams)
	{

		$connstring = ($connectionParams[0] . $connectionParams[1] . $connectionParams[2]);

		if(array_key_exists($connstring,Database::$_connections))
		{
			return Database::$_connections[$connstring];
		}

		
		
		$conn = new PDO($connectionParams[0],$connectionParams[1],$connectionParams[2]);//,array(PDO::ATTR_PERSISTENT => true));

		$conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		
		Database::$_connections[$connstring] = new DBconnection($conn);

		Return Database::$_connections[$connstring];
	}

	/**
	 * 
	 * @param DatabaseCommand $databaseCmd
	 */
	public static function getStatement($databaseCmd)
	{
		$conn = Database::getConnection($databaseCmd->getConnectionParams());

		if ($conn->isTransactionInProgress) {
			//!DatabaseCommand::$_isTransaction()
			//Debug.Assert(_isTransactionInProgress, "Transaction not in Progress while attempting to get a command for it.")
			//Return New SqlCommand(command.Sql, conn, _transaction)
		}

		return $conn->connection->prepare($databaseCmd->getSQL());
	}

	/**
	 * 
	 * @param DatabaseCommand $databaseCmd
	 */
	Public static Function executeReader($databaseCmd)
	{
		try{
			$stmt = Database::getStatement($databaseCmd);
			$stmt->execute($databaseCmd->getParams());
				
			return new CustomDataReader($stmt);
				
		}catch (PDOException $err) {
				
			var_dump($err);
		}
	}


	/**
	 * Executes SQL and returns first value in first row
	 *
	 * @param DatabaseCommand $databaseCmd
	 * @return var
	 */
	Public static Function executeScalar($databaseCmd)
	{

		try{
			$stmt = Database::getStatement($databaseCmd);
			$stmt->execute($databaseCmd->getParams());

			$arr = ($stmt->fetch(PDO::FETCH_NUM));

			return $arr[0];

		}catch (PDOException $err) {

			var_dump($err);
		}

	}

	Public static Function execute($databaseCmd)
	{

		try{
			$stmt = Database::getStatement($databaseCmd);
			return $stmt->execute($databaseCmd->getParams());

		}catch (PDOException $err) {

			var_dump($err);
		}
	}

	public static function BeginTransaction($connParams)
	{

		$conn = Database::getConnection($connParams);

		if($conn->isTransactionInProgress)
		die("TODO: NO DOUBLE TRANSACTIONS!");

		$conn->connection->beginTransaction();
		$conn->isTransactionInProgress = true;

	}

	public static function CommitTransaction($connParams)
	{
		$conn = Database::getConnection($connParams);

		if(!$conn->isTransactionInProgress)
		die("TODO: TRANS NOT IN PROGRESS!");

		$conn->connection->commit();
		$conn->isTransactionInProgress = false;
	}


	public static function RollbackTransaction($connParams)
	{
		$conn = Database::getConnection($connParams);

		if(!$conn->isTransactionInProgress)
		die("TODO: TRANS NOT IN PROGRESS!");

		$conn->connection->rollBack();
		$conn->isTransactionInProgress = false;
	}

}

?>