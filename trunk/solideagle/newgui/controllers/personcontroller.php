<?php

require_once 'data_access/Person.php';
require_once 'basecontroller.php';
use DataAccess\Person;

basecontroller::load(new CourseController());

class PersonController
{
    
}

switch ($_GET["q"])
{
	case "js":?>
	
	$(function() {
			updateUniform();
	 });
	
	<?php 
	break;
	case "content":?>

<div id="addPerson">

    <form action="#" method="post" id="formAddAccount">
        <dl>
            <div id="basicInfo">
                <h3>Basisinformatie</h3>
                <dt><label for="txtFirstName">Voornaam:</label></dt>
                <dd><input type="text" id="txtFirstName" name="txtFirstName" /></dd>
                <dt><label for="txtName">Naam:</label></dt>
                <dd><input type="text" id="txtName" name="txtName" /></dd>
                <dt><label for="radioGender">Geslacht:</label></dt>
                <dd>
                    <input type="radio" id="radioGenderMale" name="radioGender" value="M" />
                    <label for="radioGenderMale">Man</label>
                    <input type="radio" id="radioGenderFemale" name="radioGender" value="V" />
                    <label for="radioGenderFemale">Vrouw</label>
                </dd>
                <dt><label for="txtBirthDate">Geboortedatum:</label></dt>
                <dd><input type="text" id="txtBirthDate" name="txtBirthDate" /></dd>
                <dt><label for="txtBirthPlace">Geboorteplaats:</label></dt>
                <dd><input type="text" id="txtBirthPlace" name="txtBirthPlace" /></dd>
                <dt><label for="txtNationality">Nationaliteit:</label></dt>
                <dd><input type="text" id="txtNationality" name="txtNationality" /></dd>        
                <dt><label for="txtOtherInformation">Extra informatie:</label></dt>
                <dd><input type="text" id="txtOtherInformation" name="txtOtherInformation" /></dd>
                <dt><label for="txtStudentPreviousSchool">Vorige school student:</label></dt>
                <dd><input type="text" id="txtStudentPreviousSchool" name="txtStudentPreviousSchool" /></dd>
                <dt><label for="txtStudentStamnr">Stamnummer student:</label></dt>
                <dd><input type="text" id="txtStudentStamnr" name="txtStudentStamnr" /></dd>
                <dt><label for="txtParentOccupation">Beroep ouder:</label></dt>
                <dd><input type="text" id="txtParentOccupation" name="txtParentOccupation" /></dd>
            </div>
            
            <div id="contactInfo">
                <h3>Contact informatie</h3>
                <dt><label for="txtStreet">Straatnaam:</label></dt>
                <dd><input type="text" id="txtStreet" name="txtStreet" /></dd>            
                <dt><label for="txtHouseNumber">Huisnummer:</label></dt>
                <dd><input type="text" id="txtHouseNumber" name="txtHouseNumber" /></dd>
                <dt><label for="txtPostCode">Postcode:</label></dt>
                <dd><input type="text" id="txtPostCode" name="txtPostCode" /></dd>
                <dt><label for="txtCity">Gemeente:</label></dt>
                <dd><input type="text" id="txtCity" name="txtCity" /></dd>
                <dt><label for="txtCountry">Land:</label></dt>
                <dd><input type="text" id="txtCountry" name="txtCountry" /></dd>
                <dt><label for="txtEmail">E-mailadres:</label></dt>
                <dd><input type="text" id="txtEmail" name="txtEmail" /></dd>
                <dt><label for="txtPhone">Telefoonnummer:</label></dt>
                <dd><input type="text" id="txtPhone" name="txtPhone" /></dd>
                <dt><label for="txtPhone2">Telefoonnummer 2:</label></dt>
                <dd><input type="text" id="txtPhone2" name="txtPhone2" /></dd>
                <dt><label for="txtMobile">GSM-nummer:</label></dt>
                <dd><input type="text" id="txtMobile" name="txtMobile" /></dd>
            </div>
            
            <div id="accountInfo">
                <h3>Account informatie</h3>
                <dt><label for="cbActive">Account actief:</label></dt>
                <dd><input type="checkbox" id="cbActive" name="cbActive" checked="true" /></dd>
                <dt><label for="txtActiveUntill">Actief tot:</label></dt>
                <dd><input type="text" id="txtActiveUntill" name="txtActiveUntill" /></dd>
                <dt><label for="txtActiveFrom">Actief vanaf:</label></dt>
                <dd><input type="text" id="txtActiveFrom" name="txtActiveFrom" /></dd>
                <dt><label for="txtPictureUrl">Foto locatie:</label></dt>
                <dd><input type="text" id="txtPictureUrl" name="txtPictureUrl" /></dd>
                <dt><label for="cbActive">Homedir:</label></dt>
                <dd><input type="checkbox" id="cbHomeDir" name="cbHomeDir" checked="true" /></dd>
                <dt><label for="txtHomeDirUrl">Homedir locatie:</label></dt>
                <dd><input type="text" id="txtHomeDirUrl" name="txtHomeDirUrl" /></dd>
                <dt><label for="cbFtp">FTP-map:</label></dt>
                <dd><input type="checkbox" id="cbFtp" name="cbFtp" checked="true" /></dd>
                <dt><label for="txtFtpUrl">FTP-map:</label></dt>
                <dd><input type="text" id="txtFtpUrl" name="txtFtpUrl" /></dd>
            </div>
            
            
                
        </dl>
    </form>
</div>
		<?php 
	break;
}



?>