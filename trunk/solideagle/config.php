<?php

/* ----------GLOBAL CONFIG VARS---------- */

define('AD_DC', 'DC=solideagle,DC=lok');
define('AD_GROUPS_OU', 'groepen');
define('AD_USERS_OU', 'gebruikers');
define('AD_LDAPS_URL', 'ldaps://S1.solideagle.lok');
define('AD_USERNAME', 'Administrator@solideagle.lok');
define('AD_PASSWORD', 'ChaCha69');

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