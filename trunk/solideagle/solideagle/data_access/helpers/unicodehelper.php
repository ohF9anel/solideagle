<?php

namespace solideagle\data_access\helpers;

class UnicodeHelper
{
	
	public static function substr_unicode($str, $s, $l = null) {
		return join("", array_slice(
				preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}
	
	//cleans string for making mail groups
	public static function cleanEmailString($email)
	{
		$email = self::cleanUTFChars($email);
		return preg_replace("/[^A-Za-z0-9]/", "", $email);
	}
	
	//removes all utf8 chars
	public static function cleanUTFChars($string)
	{
		return iconv("UTF-8", "ASCII//TRANSLIT",$string);
	}
	
}

?>