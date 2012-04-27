<?php

namespace solideagle\test;

use solideagle\data_access\Type;
use solideagle\data_access\Person;
use solideagle\data_access\Group;
use solideagle\data_access\validation\Validator;
use solideagle\data_access\TaskTemplate;
use solideagle\plugins\ad;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

//$user = Person::getPersonById(158);
//echo "<pre>";
//var_dump($user);
//echo "</pre>";

//var_dump(ldap_search($connLdap->getConn(), Config::singleton()->ad_dc, "(sAMAccountName=eagles12)"));

//\solideagle\plugins\ad\ManageUser::setHomeFolder("llnd12", "\\\\S1\\llnd12$");
//$tt = TaskTemplate::getTemplateByName("hehe");
//var_dump(unserialize($tt->getTemplateConfig()));

//var_dump(Validator::validateString("转注字轉注字éééfé", 10, 10));

var_dump(\solideagle\Config::singleton()->ad_password);
//var_dump($user->isTypeOf(Type::TYPE_LEERLING));


?>
