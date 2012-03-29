<?php

namespace solideagle\plugins\ad;

use solideagle\data_access\Person;
use solideagle\data_access\Group;

set_include_path(get_include_path().PATH_SEPARATOR."../../");

spl_autoload_extensions(".php"); // comma-separated list
spl_autoload_register();

$user = new User();

$person = Person::getPersonById(85);

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

$user->setEnabled($person->getAccountActive());
$user->addMemberOfGroups(Group::getGroupById($person->getGroupId()));

$parents = Group::getParents(Group::getGroupById($person->getGroupId()));

var_dump("start updating");
ManageUser::updateUser($user->getUserInfo(), $parents);

?>
