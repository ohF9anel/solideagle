<?php

namespace Database;

class Database
{
	private static $_isTransactionInProgress = false;
	private static $_connections = array();


	//$connectionParams[0] = DSN
	//$connectionParams[1] = username
	//$connectionParams[2] = password	
	public static function getConnection($connectionParams) 
	{

		if(array_key_exists($connecionString,$_connections))
		{
			return $_connections[$connectionString];
		}
		
		$dbh = new PDO($connectionArr[0],$connectionArr[1],$connectionArr[2]);

		$_connections[$connectionString] = $dbh;
		
		Return $dbh;
	}

	public static function getStatement($databaseCmd)
	{
		$conn = getConnection($databaseCmd->getConnectionParams);
		
		if ($databaseCmd->isTransaction) {			
			//Debug.Assert(_isTransactionInProgress, "Transaction not in Progress while attempting to get a command for it.")
			//Return New SqlCommand(command.Sql, conn, _transaction)
		}
		
		$conn->prepare($databaseCmd->getSQL);
	}

	Public static Function executeReader($databaseCmd)
	{
		$stmt = getStatement($databaseCmd);
		$stmt->execute($databaseCmd->getParams);
		
		return new CustomDataReader($stmt);
	
	}
	
	Public static Function executeScalar($databaseCmd)
	{
		$stmt = getStatement($databaseCmd);
		$stmt->execute($databaseCmd->getParams);
		
		return $stmt->fetch(PDO::FETCH_NUM);
		
	}
	
	Public static Function execute($databaseCmd)
	{
		$stmt = getStatement($databaseCmd);
		$stmt->execute($databaseCmd->getParams);
		$stmt->closeCursor();
	}








}

?>