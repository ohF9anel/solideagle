<?php

include('Net/SSH2.php');

define('NET_SSH2_LOGGING', NET_SSH2_LOG_SIMPLE);

$ssh = new Net_SSH2('S34');
var_dump($ssh->fsock);


?>
