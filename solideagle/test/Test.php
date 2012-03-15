<?php

include('Net/SSH2.php');

$ssh = new Net_SSH2('10.3.7.111');
if (!$ssh->login('Administrator@solideagle.lok', 'ChaCha69')) {
	exit('Login Failed');
}

echo $ssh->exec('ls');



?>
