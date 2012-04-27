<?php

namespace solideagle\test\smartschool;

use solideagle\plugins\smartschool\data_access\User;
use solideagle\plugins\smartschool\data_access\Api;
use solideagle\data_access\Group;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();


//echo "<pre>";

var_dump((base64_decode(Api::singleton()->getAllGroupsAndClasses())));

//echo "</pre>";
function xmldecode($txt)
{
    $txt = str_replace('&amp;',		'&',	$txt);
    $txt = str_replace('&lt;',		'<',	$txt);
    $txt = str_replace('&gt;',		'>',	$txt);
    $txt = str_replace('&apos;',	"'",	$txt);
    $txt = str_replace('&quot;', 	'"',	$txt);
    return $txt;
}

?>
