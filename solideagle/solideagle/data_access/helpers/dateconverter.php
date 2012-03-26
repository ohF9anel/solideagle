<?php

namespace solideagle\data_access\helpers;


class DateConverter
{
	
	public static function timestampDateToDb($timestamp)
	{
		return date("YmdHis",$timestamp);
	}
	
	
}



?>