<?php

namespace solideagle\utilities;

class SuperEntities
{
	public static function encode( $str ){
		// get rid of existing entities else double-escape
		$str = html_entity_decode(stripslashes($str),ENT_QUOTES,'UTF-8');
		$ar = preg_split('/(?<!^)(?!$)/u', $str );  // return array of every multi-byte character
		$str2 = "";
		foreach ($ar as $c){
			$o = ord($c);
			if ( (strlen($c) > 1) || /* multi-byte [unicode] */
					($o <32 || $o > 126) || /* <- control / latin weirdos -> */
					($o >33 && $o < 40) ||/* quotes + ambersand */
					($o >59 && $o < 63) /* html */
			) {
				// convert to numeric entity
				$c = mb_encode_numericentity($c,array (0x0, 0xffff, 0, 0xffff), 'UTF-8');
			}
			$str2 .= $c;
		}
		return $str2;
	}
}


?>