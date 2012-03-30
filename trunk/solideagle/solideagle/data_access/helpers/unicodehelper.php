<?php

namespace solideagle\data_access\helpers;

class UnicodeHelper
{
	
	public static function substr_unicode($str, $s, $l = null) {
		return join("", array_slice(
				preg_split("//u", $str, -1, PREG_SPLIT_NO_EMPTY), $s, $l));
	}
	
}

?>