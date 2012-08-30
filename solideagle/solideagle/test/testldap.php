<?php

/*
* // basic sequence with LDAP is connect, bind, search, interpret search
* // result, close connection
 */


echo "<h3>LDAP query test</h3>\n";
echo "Connecting ...\n";
$ds=ldap_connect("atlas5.dbz.lok");  // must be a valid LDAP server!
echo "connect result is " . $ds . "<br />\n";

if ($ds) { 
    echo "Binding ...\n"; 
    $r=ldap_bind($ds);     // this is an "anonymous" bind, typically
                           // read-only access
    echo "Bind result is " . $r . "<br />\n";

    echo "Searching for (sn=S*) ...\n";
    // Search surname entry
    $sr=ldap_search($ds, "dc=dbz,dc=lok", "sn=S*");  
    echo "Search result is " . $sr . "<br />\n";

    echo "Number of entries returned is " . ldap_count_entries($ds, $sr) . "<br />\n";

    echo "Getting entries ...<p>\n";
    $info = ldap_get_entries($ds, $sr);
    echo "Data for " . $info["count"] . " items returned:<p>\n";

    for ($i=0; $i<$info["count"]; $i++) {
        echo "dn is: " . $info[$i]["dn"] . "<br />\n";
        echo "first cn entry is: " . $info[$i]["cn"][0] . "<br />\n";
        echo "first email entry is: " . $info[$i]["mail"][0] . "<br /><hr />\n";
    }

    echo "Closing connection\n";
    ldap_close($ds);

} else {
    echo "\n<h4>Unable to connect to LDAP server</h4>";
}
?>
