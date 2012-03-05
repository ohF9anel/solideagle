<?php

namespace validation;

class Validator
{
	
	public static function validateString($string,$minlength,$maxlength,$allowSpecialChars)
	{
		
	}

	public static function validateNumber($val,$minval = 0,$maxval = NULL)
	{
		
	}
	
	/**
	 * 
	 * $val is the value to be checked, $enum is an array with possible values
	 * 
	 * @param var $val
	 * @param array $enum
	 * @return boolean
	 */
	public static function validateEnum($val,$enum)
	{
		foreach ($enum as $eval)
		{
			if($val == $eval)
			{
				return true;
			}
		}
	}
	
}

?>