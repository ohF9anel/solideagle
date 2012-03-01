<?php
require_once 'config.php';
require_once 'Smartschool/User.php';


$user = new Smartschool\User();

$user->setInternnumber("smartschool_plugin3");
$user->setUsername("smartschool_plugin_user3");
$user->setPasswd1("12345");
$user->setName("smartschool");
$user->setSurname("pluginaanpassing");
$user->setBasisrol("leerling");
$user->setAccountStatus("actief");

$user->addClass("groupsecret");
$user->addClass("group2secret");

echo Smartschool\Error::getErrorFromCode(Smartschool\User::saveUser($user));

?>