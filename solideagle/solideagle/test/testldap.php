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

echo "\n<hr /><h1>Tweede test</h1>\n";
/**************************************************
  Bind to an Active Directory LDAP server and look
  something up.
***************************************************/
  $SearchFor="demedtsj";               //What string do you want to find?
  $SearchField="samaccountname";   //In what Active Directory field do you want to search for the string?

  $LDAPHost = "atlas5.dbz.lok";       //Your LDAP server DNS Name or IP Address
  $dn = "DC=dbz,DC=lok"; //Put your Base DN here
  
  require "/root/solideagle/dbz_config.inc";
  $LDAPUser = DBZ_AD_SOLIDEAGLE_USER;
        // todo: use non svn non web constant for this
  $LDAPUserPassword = DBZ_AD_SOLIDEAGLE_PASSWORD;
        // todo: generate a fatal error when empty
  $LDAPUserDomain = "@dbz.lok";  //Needs the @, but not always the same as the LDAP server domain
//  $LDAPUser = "ldapuserid";        //A valid Active Directory login
//  $LDAPUserPassword = "passforuser";
  $LDAPFieldsToFind = array("cn", "givenname", "samaccountname", "homedirectory", "telephonenumber", "mail");
   
  $cnx = ldap_connect($LDAPHost) or die("Could not connect to LDAP");
  ldap_set_option($cnx, LDAP_OPT_PROTOCOL_VERSION, 3);  //Set the LDAP Protocol used by your AD service
  ldap_set_option($cnx, LDAP_OPT_REFERRALS, 0);         //This was necessary for my AD to do anything
  ldap_bind($cnx,$LDAPUser.$LDAPUserDomain,$LDAPUserPassword) or die("Could not bind to LDAP");
  error_reporting (E_ALL ^ E_NOTICE);   //Suppress some unnecessary messages
  $filter="($SearchField=$SearchFor*)"; //Wildcard is * Remove it if you want an exact match
  $sr=ldap_search($cnx, $dn, $filter, $LDAPFieldsToFind);
  $info = ldap_get_entries($cnx, $sr);
 
  for ($x=0; $x<$info["count"]; $x++) {
    $sam=$info[$x]['samaccountname'][0];
    $giv=$info[$x]['givenname'][0];
    $tel=$info[$x]['telephonenumber'][0];
    $email=$info[$x]['mail'][0];
    $nam=$info[$x]['cn'][0];
    $dir=$info[$x]['homedirectory'][0];
    $dir=strtolower($dir);
    $pos=strpos($dir,"home");
    $pos=$pos+5;
    if (stristr($sam, "$SearchFor") && (strlen($dir) > 8)) {
      print "\nActive Directory says that:\n";
      print "CN is: $nam \n";
      print "SAMAccountName is: $sam \n";
      print "Given Name is: $giv \n";
      print "Telephone is: $tel \n";
      print "Home Directory is: $dir \n";
    }  
  }  
  if ($x==0) { print "Oops, $SearchField $SearchFor was not found. Please try again.\n"; }

?>
