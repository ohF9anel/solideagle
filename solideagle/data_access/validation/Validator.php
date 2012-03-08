<?php

namespace Validation;

class ValidationError {
    
    const IS_NULL = 0;
    const STRING_TOO_LONG = 1;
    const STRING_TOO_SHORT = 2;
    const STRING_HAS_SPECIAL_CHARS = 3;
    const INT_TOO_SMALL = 4;
    const INT_TOO_LARGE = 5;
    const PSW_NO_LOWER_CASE = 6;
    const PSW_NO_UPPER_CASE = 7;
    const PSW_NO_NUMBER = 8;
    const DATE_BAD_SYNTAX = 9;
    const DATE_IS_FUTURE = 10;
    const DATE_IS_PAST = 11;
    const DATE_DOES_NOT_EXIST = 12;
    const TIME_DOES_NOT_EXIST = 13;
    const NO_NUMBER = 14;
    
}

class Validator
{

	public static function validateString($string, $minlength, $maxlength, $allowSpecialChars = true)
	{
		$valErrors = array();
		if(empty($string) && $minlength > 0){
			$valErrors[] = ValidationError::IS_NULL;
		}elseif(strlen($string) < $minlength)
		{
			$valErrors[] = ValidationError::STRING_TOO_SHORT;
		}
		if(strlen($string) > $maxlength)
		{
			$valErrors[] = ValidationError::STRING_TOO_LONG;
		}
 		if (!$allowSpecialChars)
 		{
                        // allow a-z A-Z 0-9 '.' '-' '_' ' '
 			if(!preg_match('/^[A-Za-z0-9_-\s\.]*$/', $string)) 
 			{
 				//$valErrors[] = ValidationError::STRING_HAS_SPECIAL_CHARS;
 			}
 		}
		return $valErrors;
	}
        
        public static function validatePassword($psw, $minLength, $maxLength)
        {
                $valErrors = Validator::validateString($psw, $minLength, $maxLength, true);

                if( !preg_match("#[0-9]+#", $psw) )
                        $valErrors[] = ValidationError::PSW_NO_NUMBER;
                
                if( !preg_match("#[a-z]+#", $psw) )
                        $valErrors[] = ValidationError::PSW_NO_LOWER_CASE;

                if( !preg_match("#[A-Z]+#", $psw) )
                        $valErrors[] = ValidationError::PSW_NO_UPPER_CASE;

                return $valErrors;
        }

	public static function validateInt($val, $minval = 0, $maxval = NULL)
	{
		$valErrors = array();
                if(!is_numeric($val))
                {
                        $valErrors[] = ValidationError::NO_NUMBER;
                }
                else{
                    if(empty($val))
                    {
                            $valErrors[] = ValidationError::IS_NULL;
                    }
                    else if($val < $minval)
                    {
                            $valErrors[] = ValidationError::INT_TOO_SMALL;
                    }
                    else if($val > $maxval)
                    {
                            $valErrors[] = ValidationError::INT_TOO_BIG;
                    }
                }

		return $valErrors;
	}
        
        public static function validateDate($date)
        {
            $valErrors = array();
            if(strlen($date) != 8 || !is_numeric($date)) {
                $valErrors[] = ValidationError::DATE_BAD_SYNTAX;
            }
            else {
                $year = substr($date, 0, 4);
                $month = substr($date, 4, 2);
                $day = substr($date, 6, 2);
                
                if (!checkdate($month, $day, $year)) {
                    $valErrors[] = ValidationError::DATE_DOES_NOT_EXIST;
                }
            }
            
            return $valErrors;
        }
        
        public static function validateDateTime($dateTime)
        {                
            $valErrors = Validator::validateDate(substr($dateTime, 0, 8));
            
            if ($valErrors == null)
            {
                if (strlen($dateTime) != 14)
                {
                    $valErrors[] = ValidationError::DATE_BAD_SYNTAX;
                }
                else {
                    $time = substr($dateTime, 8, 6);
                    $hour = substr($time, 0, 2);
                    $minute = substr($time, 2, 2);
                    $second = substr($time, 4, 2);

                    if ($hour > 23 || $minute > 59 || $second > 59) {
                        $valErrors[] = ValidationError::TIME_DOES_NOT_EXIST;
                    }
                }
            }

            return $valErrors;
        }
        
        /**
         * Checks if date happens in the future of the past
         * @param int $date
         * @param bool $past    true = checks if date is in past, false = checks if date is in future
         * @return array 
         */
        public static function validateDateOccurrence($date, $checkIfPast)
        {
            $valErrors = Validator::validateDate($date);
            if (sizeof($valErrors) == 0)
            {
                if ($checkIfPast)
                {
                    if ($date > date('Ymd'))
                        $valErrors[] = ValidationError::DATE_IS_FUTURE;             
                }
                else
                {
                    if ($date < date('Ymd'))
                        $valErrors[] = ValidationError::DATE_IS_PAST;             
                } 
            }
            return $valErrors;
        }
        
        /**
         * Checks if datetime happens in the future of the past
         * @param int $dateTime
         * @param bool $past    true = checks if datetime is in past, false = checks if datetime is in future
         * @return array 
         */
        public static function validateDateTimeOccurrence($dateTime, $checkIfPast)
        {
            $valErrors = Validator::validateDateTime($dateTime);
            if (sizeof($valErrors) == 0)
            {
                if ($checkIfPast)
                {
                    if ($dateTime > date('YmdHis'))
                    {
                        $valErrors[] = ValidationError::DATE_IS_FUTURE; 
                    }      
                }
                else
                {
                    if ($dateTime < date('YmdHis'))
                        $valErrors[] = ValidationError::DATE_IS_PAST;              
                } 
            }
            
            return $valErrors;
        }         
        
        public static function validateEmailAddress($emailAddress)
        {
            $valErrors = array();
            if ($emailAddress != "")
            {
                if(!preg_match("/^([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+$/", $emailAddress))
                {
                    $valErrors[] = ValidationError::EMAIL_ADDRESS_INVALID;
                }
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
