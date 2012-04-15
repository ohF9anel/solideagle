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
		if(!is_numeric($dbdate))
			return NULL;

		return substr ($dbdate,6,2) . "-" . substr($dbdate,4,2) . "-" . substr($dbdate,0,4);
	}

	public static function DisplayDateTodbDate($displaydate)
	{
		return substr ($displaydate,6,4) . substr($displaydate,3,2) . substr($displaydate,0,2);
	}

	public static function longDbDateToDisplayDate($longDbDate)
	{
		if(!is_numeric($longDbDate))
			return NULL;

		return substr($longDbDate,0,4) . "-" . substr($longDbDate,4,2) . "-" . substr($longDbDate,6,2) 
		. " " . substr($longDbDate,8,2) . ":" . substr($longDbDate,10,2). ":" . substr($longDbDate,12,2);
	}

}



?>