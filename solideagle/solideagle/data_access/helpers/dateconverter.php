<?php

namespace solideagle\data_access\helpers;


class DateConverter
{
	
	public static function timestampDateToDb($timestamp)
	{
		return date("YmdHis",$timestamp);
	}
	
	public static function dbDateToDisplayDate($dbdate)
	{
		return substr ($dbdate,6,2) . "-" . substr($dbdate,4,2) . "-" . substr($dbdate,0,4);
	}
	
	public static function DisplayDateTodbDate($displaydate)
	{
		return substr ($displaydate,6,4) . substr($dbdate,3,2) . substr($dbdate,0,2);
	}
	
}



?>