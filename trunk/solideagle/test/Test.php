<?php



include('Net/SSH2.php');

define('NET_SSH2_LOGGING', NET_SSH2_LOG_SIMPLE);

$ssh = new Net_SSH2('10.3.7.111');
if (!$ssh->login('Administrator', 'ChaCha69')) {
	exit('Login Failed');
}
var_dump($ssh->exec('cmd /Cdir'));
//echo $ssh->exec('ls');

$ssh->timeout = 10;

echo "<pre>";

$ssh->write("cmd\n");



$ssh->write("set naam=blaat\n");

$ssh->write("echo %naam%\n");



$ssh->write("echo ENDOFSESSION\n");

while($data = $conn->_get_channel_packet(NET_SSH2_CHANNEL_SHELL))
{
	echo $data;
}


echo "</pre>";


?>
