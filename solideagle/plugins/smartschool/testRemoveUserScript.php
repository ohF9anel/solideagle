<?php

require_once 'Smartschool/User.php';

$user = new Smartschool\User();
$user->internnumber = "smartschool_plugin2";

echo Smartschool\User::removeUser($user);

?>