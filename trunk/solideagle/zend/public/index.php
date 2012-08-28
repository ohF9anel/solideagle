<?php

if( !isset($_SERVER['REMOTE_ADDR']) || (
    (strpos($_SERVER['REMOTE_ADDR'], "127.0.") === false ) &&
	(strpos($_SERVER['REMOTE_ADDR'], "10.12.1.") === false ) && 
	(strpos($_SERVER['REMOTE_ADDR'], "10.9.1.") === false)
        )
) {
  exit("No access for: " . $_SERVER['REMOTE_ADDR']);
}


ini_set('default_charset','UTF-8');
date_default_timezone_set("Europe/Brussels");
setlocale(LC_CTYPE, 'en_US.utf8'); //need to use an installed locale!!!


// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
/*
 * possible options: production, staging, testing, development
 * see /solideagle/zend/application/configs/application.ini
 */
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();
