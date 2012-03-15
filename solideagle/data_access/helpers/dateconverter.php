<?php

namespace DataAcces;


class DateConverter
{
	
	public static function timestampDateToDb($timestamp)
	{
		return date("YmdHis",$timestamp);
	}
	
	
}



?>