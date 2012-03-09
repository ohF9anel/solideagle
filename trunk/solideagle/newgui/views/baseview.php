<?php



class baseview
{

	public static function load($instance)
	{
		
	
		call_user_func(array($instance,$_GET["q"]));
	
	}
}


?>