<?php

namespace solideagle\data_access\helpers;

class UnicodeHelper
{
	
	public static function substr_unicode($str, $s, $l = null) {
		return join("", array_slice(
				preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}
	
	//cleans string for making mail groups
	//actually just removes all special chars and is used to make unique group names
	//these unique group names are used for mail and smartschool group codes
	//and to make sure there are no groups that collide
	//
	//should probably be renamed
	public static function cleanEmailString($email)
	{
		$email = self::cleanUTFChars($email);
		return strtolower(preg_replace("/[^A-Za-z0-9]/", "", $email));
	}
	
	//see cleanEmailstring
	public static function cleanSmartschoolCodeString($input)
	{
		return self::cleanEmailString($input); //samestuff as email cleaning
	}
	
	//replaces special chars with their equivalent
	//example é->e à -> a etc...
	public static function cleanUTFChars($string)
	{
		return iconv("UTF-8", "ASCII//TRANSLIT",$string);
	}
	
}

?>
