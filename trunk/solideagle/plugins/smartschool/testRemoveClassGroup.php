<?php
require_once 'config.php';
require_once 'Smartschool/ClassGroup.php';

$classgroup = new Smartschool\ClassGroup();


$classgroup->setCode("group2secret");


echo Smartschool\ClassGroup::deleteClassGroup($classgroup);

?>
