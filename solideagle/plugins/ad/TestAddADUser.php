<?php

namespace AD;

require_once('User.php');
require_once('data_access/Person.php');
require_once('ConnectionLdap.php');

use DataAccess\Person;

$user = new User();

$person = Person::getPersonById(44);

$user->setCn($person->first_name . ' ' . $person->name . ' (' . $person->account_username . ')');
$user->setUid($person->account_username);
$user->setSAMAccountName($person->account_username);
$user->setUnicodePwd('Azerty123');
$user->setSn($person->name);
$user->setGivenname($person->first_name);
$user->setUserprincipalname($person->account_username);
$user->setDisplayName($person->first_name . ' ' . $person->name);
$user->setStreetaddress($person->street);
$user->setPostofficebox($person->house_number);
$user->setPostalcode($person->post_code);
$user->setL($person->city);
$user->setCo($person->country);
$user->setHomephone($person->phone);
$user->setMobile($person->mobile);
$user->setMail($person->email);
$user->setInfo($person->other_information);

$conn = new ConnectionLDAP();

$dn = 'CN=' . $user->getCn() . ',OU=gebruikers,DC=solideagle,DC=lok';

$conn->addUser($user->getUserInfo(), $dn, true);

?>
