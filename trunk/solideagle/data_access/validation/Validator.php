<?php

namespace Validation;

class ValidationError {

	const IS_NULL = 0;
	const STRING_TOO_LONG = 1;
	const STRING_TOO_SHORT = 2;
	const STRING_HAS_SPECIAL_CHARS = 3;
	const INT_TOO_SMALL = 4;
	const INT_TOO_LARGE = 5;

}

class Validator
{

	public static function validateString($string, $minlength, $maxlength, $allowSpecialChars = true)
	{
		$valErrors = array();
		if(empty($string) && $minlength > 0){
			$valErrors[] = ValidationError::IS_NULL;
		}
		if(strlen($string) < $minlength)
		{
			$valErrors[] = ValidationError::STRING_TOO_SHORT;
		}
		if(strlen($string) > $maxlength)
		{
			$valErrors[] = ValidationError::STRING_TOO_LONG;
		}
// 		if (!$allowSpecialChars)
// 		{
// 			if(!ctype_alnum($string))
// 			{
// 				$valErrors[] = ValidationError::STRING_HAS_SPECIAL_CHARS;
// 			}
// 		}
		return $valErrors;
	}

	public static function validateNumber($val, $minval = 0, $maxval = NULL)
	{
		$valErrors = array();
		if(empty($val)){
			$valErrors[] = ValidationError::IS_NULL;
		}
		if($val < $minlength)
		{
			$valErrors[] = ValidationError::INT_TOO_SMALL;
		}
		if($val > $maxlength)
		{
			$valErrors[] = ValidationError::INT_TOO_BIG;
		}

		return $valErrors;
	}

	/**
	 *
	 * $val is the value to be checked, $enum is an array with possible values
	 *
	 * @param var $val
	 * @param array $enum
	 * @return boolean
	 */
	public static function validateEnum($val, $enum)
	{
		foreach ($enum as $eval)
		{
			if($val == $eval)
			{
				return true;
			}
		}

		return false;
	}

}

?>