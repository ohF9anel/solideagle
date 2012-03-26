<?php
//require_once 'config.php';
require_once 'plugins/smartschool/data_access/SSUser.php';

use Smartschool\SSUser;
use DataAccess\Person;
use Smartschool\Error;

$user = new SSUser();

$user = SSUser::convertPersonToSsUser(Person::getPersonById(85));
var_dump($user);
echo Error::getErrorFromCode(SSUser::saveUser($user));

?>