<?php

namespace AD;

require_once('User.php');
require_once('data_access/Person.php');
require_once('data_access/Group.php');
require_once('ConnectionLdap.php');
require_once('ManageUser.php');

use DataAccess\Person;
use DataAccess\Group;

$user = new User();

$person = Person::getPersonById(71);

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
$user->setHomedirectory("\\\\S1\\kellyv12");

$user->setEnabled($person->getAccountActive());
$user->addMemberOfGroups($person->getGroups());

$group = $person->getGroups();

$groups = Group::getParents($group[0]);

ManageUser::addUser($user->getUserInfo(), $groups);

?>
