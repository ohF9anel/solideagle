<?php

namespace solideagle\plugins\smartschool;

require_once 'config.php';
use SoapClient;


class Api
{
	private static $instance;
	private $apiKey;
	private $client;

	private function __construct()
	{
		$this->client = new SoapClient(Config::singleton()->ss_ws_url);
		$this->apiKey = Config::singleton()->ss_ws_psw;
	}

	public static function singleton()
	{
		if (!isset(self::$instance)) {
			$className = __CLASS__;
			self::$instance = new $className;
		}
		return self::$instance;
	}

	public function addCourse ($coursename, $coursedesc)
	{
		return $this->client->addCourse($this->apiKey, $coursename, $coursedesc);
	}
	public function addCourseStudents ($coursename, $coursedesc, $groupIds)
	{
		return $this->client->addCourseStudents($this->apiKey, $coursename, $coursedesc, $groupIds);
	}
	public function addCourseTeacher ($coursename, $coursedesc, $internnummer, $userlist)
	{
		return $this->client->addCourseTeacher($this->apiKey, $coursename, $coursedesc, $internnummer, $userlist);
	}
	public function delClass ($code)
	{
		return $this->client->delClass($this->apiKey, $code);
	}
	public function delUser ($userIdentifier)
	{
		return $this->client->delUser($this->apiKey, $userIdentifier);
	}
	public function getAllAccounts ($code, $recursive)
	{
		return $this->client->getAllAccounts($this->apiKey, $code, $recursive);
	}

	public function getAllGroupsAndClasses ()
	{
		return $this->client->getAllGroupsAndClasses($this->apiKey);
	}
	public function getClassList ()
	{
		return $this->client->getClassList($this->apiKey);
	}
	public function getCourses ()
	{
		return $this->client->getCourses($this->apiKey);
	}
	public function replaceInum ($oldInum, $newInum)
	{
		return $this->client->replaceInum($this->apiKey, $oldInum, $newInum);
	}
	public function returnCsvErrorCodes ()
	{
		return $this->client->returnCsvErrorCodes($this->apiKey);
	}
	public function returnJsonErrorCodes ()
	{
		return $this->client->returnJsonErrorCodes($this->apiKey);
	}
	public function saveClass ($name, $desc, $code, $parent, $untis, $instituteNumber, $adminNumber)
	{
		return $this->client->saveClass($this->apiKey, $name, $desc, $code, $parent, $untis, $instituteNumber, $adminNumber);
	}
	public function saveClassList ($serializedList)
	{
		return $this->client->saveClassList($this->apiKey, $serializedList);
	}
	public function savePassword ($userIdentifier, $password, $accountType = 0)
	{
		return $this->client->savePassword($this->apiKey, $userIdentifier, $password, $accountType = 0);
	}
	public function saveUser ($internnumber, $username, $passwd1, $passwd2, $name, $surname, $extranames, $initials, $sex, $birthday, $birthplace, $birthcountry, $address, $postalcode, $location, $country, $email, $mobilephone, $homephone, $fax, $prn, $stamboeknummer, $basisrol, $untis)
	{
		return $this->client->saveUser($this->apiKey, $internnumber, $username, $passwd1, $passwd2, $name, $surname, $extranames, $initials, $sex, $birthday, $birthplace, $birthcountry, $address, $postalcode, $location, $country, $email, $mobilephone, $homephone, $fax, $prn, $stamboeknummer, $basisrol, $untis);
	}
	public function saveUserParameter ($userIdentifier, $paramName, $paramValue)
	{
		return $this->client->saveUserParameter($this->apiKey, $userIdentifier, $paramName, $paramValue);
	}
	public function saveUserToClass ($userIdentifier, $class)
	{
		return $this->client->saveUserToClass($this->apiKey, $userIdentifier, $class);
	}
	public function saveUserToClasses ($userIdentifier, $csvList)
	{
		return $this->client->saveUserToClasses($this->apiKey, $userIdentifier, $csvList);
	}
	public function sendMsg ($userIdentifier, $title, $body)
	{
		return $this->client->sendMsg($this->apiKey, $userIdentifier, $title, $body);
	}
	public function setAccountPhoto ($userIdentifier, $photo)
	{
		return $this->client->setAccountPhoto($this->apiKey, $userIdentifier, $photo);
	}
	public function setAccountStatus ($userIdentifier, $accountStatus)
	{
		return $this->client->setAccountStatus($this->apiKey, $userIdentifier, $accountStatus);
	}

	public function __clone()
	{
		trigger_error('Clone is not allowed.', E_USER_ERROR);
	}

	public function __wakeup()
	{
		trigger_error('Unserializing is not allowed.', E_USER_ERROR);
	}
}

class Error
{

	private static $errorArray = array( 1 => "De naam moet uit minstens 2 karakters bestaan.",
	2 => "De voornaam moet uit minstens 2 karakters bestaan.",
	3 => "De gebruikersnaam moet uit minstens 2 karakters bestaan.",
	4 => "Het nieuwe wachtwoord moet uit minstens 5 karakters bestaan.",
	5 => "Er is geen groep geselecteerd.",
	6 => "De gebruikersnaam bestaat reeds.",
	7 => "De wachtwoorden zijn niet identiek.",
	8 => "Het opgegeven webserviceswachtwoord is niet correct.",
	9 => "Deze gebruiker bestaat niet",
	10 => "Er is een fout gebeurd tijdens het verwerken van de gegevens. Er is niets toegepast.",
	11 => "Er is een fout opgetreden tijdens het bewaren van de klasgegevens.",
	12 => "Deze gebruiker bestaat niet",
	13 => "Er is een fout opgetreden tijdens het kopiëren/verplaatsen van de gebruikers naar de opgegeven klas.",
	14 => "Onvoeldoende gegeven aangeleverd.",
	15 => "Dubbele gebruikersnaam",
	16 => "Dubbele interne nummer",
	17 => "Er is een fout opgetreden tijdens het bewaren van één of meerdere profielvelden.",
	18 => "Er is een fout opgetreden bij het versturen van het bericht",
	19 => "Parent-ID bestaat niet !",
	20 => "Cursus toevoegen mislukt.",
	21 => "Cursus met zelfde naam aanwezig.",
	22 => "Cursus niet gevonden.",
	23 => "Er is een onbekende fout opgetreden tijdens de verwerking.",
	24 => "Er is reeds een gebruiker aanwezig met dit intern nummer. Gelieve een ander nummer in te geven.",
	25 => "Opgelet, de gebruiker kon niet worden gewijzigd, omdat deze niet bestaat in Smartschool.",
	26 => "Opgelet, de gebruiker kon niet worden toegevoegd, omdat deze reeds bestaat in Smartschool.",
	27 => "Opgelet, het instellingsnummer komt niet voor in Smartschool. Gelieve eerst de instelling toe te voegen.",
	28 => "Het selecteren van een basisrol is veplicht.",
	29 => "U mag de basisrol van deze account niet meer wijzigen.",
	30 => "Enkel leerlingen (basisrol leerling) mogen lid zijn van officiële klassen.",
	31 => "De leerling mag maar lid zijn van één officiële klas.",
	32 => "Een leerling moet lid zijn van één officiële klas.",
	33 => "Er is een fout opgetreden bij het correct registeren van de klasbeweging.",
	34 => "De leerling kan niet geactiveerd worden omdat hij geen lid is van een officiële klas.",
	35 => "Het instellingsnummer is verplicht bij een officiële klas.",
	36 => "U mag het type van een officiële klas niet wijzigen.",
	37 => "U mag het type van deze klas of groep niet wijzigen omdat sommige leden van deze groep of klas niet de basisrol leerling hebben.",
	38 => "U mag het type van deze klas of groep niet wijzigen omdat sommige leden van deze groep of klas reeds lid zijn van een andere officiële klas.",
	39 => "U dient een vormingscomponent te selecteren.",
	40 => "U mag de naam van een klas niet meer wijzigen.",
	41 => "U mag het administratiefnummer niet meer wijzigen.",
	42 => "U mag het instellingsnummer niet meer wijzigen.",
	43 => "U mag de vormingscomponent niet meer wijzigen.",
	44 => "De vormingscomponent is verplicht op te geven.",
	45 => "U mag het type van een klas niet wijzigen.",
	46 => "U dient het type van de groep te selecteren.",
	47 => "Er bestaat reeds een klas met dezelfde unieke klas- of groepcode",
	48 => "Het intern nummer bestaat reeds.");


	public static function getErrorFromCode($code)
	{
		if($code == 0)
			return "SUCCES";
		return Error::$errorArray[$code];
	}
	
}

?>