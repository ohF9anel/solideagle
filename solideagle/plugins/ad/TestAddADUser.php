<?php

namespace AD;

require_once('User.php');
require_once('Group.php');
require_once('data_access/Person.php');
require_once('ConnectionLdap.php');

use DataAccess\Person;

$user = new User();

$person = Person::getPersonById(59);

var_dump($person);

$user->setCn($person->getFirstName() . ' ' . $person->getName() . ' (' . $person->getAccountUserName() . ')');
$user->setUid($person->getAccountUserName());
$user->setSAMAccountName($person->getAccountUserName());
$user->setUnicodePwd($person->getAccountPassword());
$user->setSn($person->getName());
$user->setGivenname($person->getFirstName());
$user->setUserprincipalname($person->getAccountUserName());
$user->setDisplayName($person->getFirstName() . ' ' . $person->getName());
$user->setStreetaddress($person->getStreet());
$user->setPostofficebox($person->getHouseNumber());
$user->setPostalcode($person->getPostCode());
$user->setL($person->getCity());
$user->setCo($person->getCountry());
$user->setHomephone($person->getPhone());
$user->setMobile($person->getMobile());
$user->setMail($person->getEmail());
$user->setInfo($person->getOtherInformation());

$user->addMembersOfGroup($person->getGroups());

//$userGroups->addMember(new Group())


$conn = new ConnectionLDAP();

$dn = 'CN=' . $user->getCn() . ',OU=gebruikers,DC=solideagle,DC=lok';

$conn->addUser($user->getUserInfo(), $dn, true);

?>
