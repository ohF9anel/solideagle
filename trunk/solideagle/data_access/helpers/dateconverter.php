<?php

namespace DataAccess;


class DateConverter
{
	
	public static function timestampDateToDb($timestamp)
	{
		return date("YmdHis",$timestamp);
	}
	
	
}



?>