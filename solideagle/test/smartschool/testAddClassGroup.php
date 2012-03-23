<?php
require_once 'config.php';
require_once 'Smartschool/ClassGroup.php';

$classgroup = new Smartschool\ClassGroup();

$classgroup->setName("API_TEST_Parent3");
$classgroup->setCode("CLSparent3");
$classgroup->setDesc("API_TEST_Parent3");

echo Smartschool\ClassGroup::saveClassGroup($classgroup);

?>