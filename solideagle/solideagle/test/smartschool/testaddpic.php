<?php

namespace solideagle\test\smartschool;

use solideagle\plugins\smartschool\data_access\Api;
use solideagle\data_access\Group;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$img = "/var/www/tmp/tmp";

  	     $fd = fopen ($img, 'rb');
  	     $size=filesize ($img);
  	     $cont = fread ($fd, $size);
  	     fclose ($fd);
  	     $encimg = base64_encode($cont);

var_dump($encimg);

var_dump(Api::singleton()->setAccountPhoto("516565165", $encimg));


?>
