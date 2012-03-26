<?php

namespace AD;
require_once('HomeFolder.php');
require_once('WwwFolder.php');
require_once('ScanFolder.php');
require_once('DownloadFolder.php');
require_once('UploadFolder.php');

HomeFolder::createHomeFolder('10.3.7.111', 'C:\homefolders\personeel', 'bodsonb');
WwwFolder::setWwwFolder('10.3.7.111', 'C:\homefolders\personeel', 'C:\www', 'bodsonb');
ScanFolder::setScanFolder('10.3.7.111', 'C:\homefolders\personeel', 'C:\scans' , 'bodsonb');
DownloadFolder::setDownloadFolder('10.3.7.111', 'C:\homefolders\personeel', 'C:\downloads', 'bodsonb');
UploadFolder::setUploadFolder('10.3.7.111', 'C:\homefolders\personeel', 'C:\uploads', 'bodsonb');

?>
