<?php

echo phpinfo();

function test($lambda,$instanceclass)
{
	call_user_func($lambda,"test",$instanceclass);
}

class testclass
{
	
	private $testvar;
	
	function doSomething()
	{
		test(function($param,$thisInst){
			$thisInst->testvar = $param;
		},$this);
	}
	
	function printtest()
	{
		echo $testvar;
	}
}

$testcl = new testclass();
$testcl->doSomething();

//$dbh = new PDO('mysql:host=localhost;dbname=test', "root", "root");

?>