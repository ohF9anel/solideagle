<?php

namespace solideagle\test\ad;

use solideagle\plugins\ad\ManageUser;
use solideagle\data_access\Person;
use solideagle\plugins\ad\ConnectionLdap;
use solideagle\Config;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$connLdap = ConnectionLDAP::singleton();
if ($connLdap->getConn() == null)
    return new StatusReport(false, "Connection to AD cannot be made.");

$attr = array('sAMAccountName', 'homeDirectory');
$sr = ldap_search($connLdap->getConn(), "OU=staff,OU=gebruikers," . Config::singleton()->ad_dc, "(&(sAMAccountName=*))", $attr);
$entries = ldap_get_entries($connLdap->getConn(), $sr);

$fp = fopen('/var/backups/solideagle/homefolderattrstaff.csv', 'w');

foreach($entries as $key => $entry)
{
    if ($key == 0) continue;
    if (!isset($entries[$key]["homedirectory"][0])) continue;
    var_dump($entries[$key]["samaccountname"][0]);
    var_dump($entries[$key]["homedirectory"][0]);
    fputcsv($fp, array($entries[$key]["samaccountname"][0], $entries[$key]["homedirectory"][0]), ';');
}
fclose($fp);

?>
