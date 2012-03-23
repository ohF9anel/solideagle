<?php

namespace Smartschool;

require_once 'data_access/Api.php';
use Smartschool\Api;

$api = Api::singleton();

print_r($api->getCourses());



?>
