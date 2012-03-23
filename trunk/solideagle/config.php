<?php

/* ----------GLOBAL CONFIG VARS---------- */

define('AD_DC', 'DC=solideagle, DC=lok');
define('AD_NETBIOS', 'SOLIDEAGLE');
define('AD_DC_HOST', 'S1.solideagle.lok');
define('AD_GROUPS_OU', 'groepen');
define('AD_USERS_OU', 'gebruikers');
define('AD_LDAPS_URL', 'ldaps://S1.solideagle.lok');
define('AD_USERNAME', 'Administrator@solideagle.lok');
define('S1_ADMINISTRATOR', 'Administrator');
define('AD_PASSWORD', 'Azerty1234');
define('SSH_SERVER', 'S1.solideagle.lok');

define('DIR_NAME_DOWNLOADS', '_downloads');
define('DIR_NAME_UPLOADS', '_uploads');
define('DIR_NAME_SCANS', '_scans');
define('DIR_NAME_WWW', '_www');

define('SS_WS_URL', 'http://dbz-tmp.smartschool.be/Webservices/V3?wsdl');
define('SS_WS_PSW', 't2T79FbI2A5QHFNs');
/*TODO: Disable asserts here when deploying */

// Active assert and make it quiet
assert_options(ASSERT_ACTIVE, 1);
assert_options(ASSERT_WARNING, 0);
assert_options(ASSERT_QUIET_EVAL, 1);

// Create a handler function
function my_assert_handler($file, $line, $code)
{
	echo "Assertion Failed:\r\n
        File '$file'\r\n
        Line '$line'\r\n
        Code '$code'\r\n";
}

// Set up the callback
assert_options(ASSERT_CALLBACK, 'my_assert_handler');

/*----------------------------------*/

?>