<?php

require_once 'baseview.php';

baseview::load(new person);

class person
{
    
    public function getcontent()
    {
        ?>
            <div id="addPerson">

                <form action="#" method="post" id="formAddAccount">
                    <div id="basicInfo">
                        <h3>Basisinformatie</h3>
                        <p>
                            <label for="txtNameCourse">Naam vak:</label>
                            <input type="text" id="txtNameCourse" name="coursename" />			
                        </p>
                        <p>
                            <label for="txtFirstName">Voornaam:</label>
                            <input type="text" id="txtFirstName" name="txtFirstName" />
                        <p>
                            <label for="txtName">Naam:</label>
                            <input type="text" id="txtName" name="txtName" />
                        </p>
                        <p>
                            <label for="radioGender">Geslacht:</label>
                            <input type="radio" id="radioGenderMale" name="radioGender" value="M" />
                            <label for="radioGenderMale">Man</label>
                            <input type="radio" id="radioGenderFemale" name="radioGender" value="V" />
                            <label for="radioGenderFemale">Vrouw</label>
                        </p>
                        <p>
                            <label for="txtBirthDate">Geboortedatum:</label>
                            <input type="text" id="txtBirthDate" name="txtBirthDate" />
                        </p>
                        <p>
                            <label for="txtBirthPlace">Geboorteplaats:</label>
                            <input type="text" id="txtBirthPlace" name="txtBirthPlace" />
                        </p>
                        <p>
                            <label for="txtNationality">Nationaliteit:</label>
                            <input type="text" id="txtNationality" name="txtNationality" /> 
                        </p>
                        <p>
                            <label for="txtOtherInformation">Extra informatie:</label>
                            <input type="text" id="txtOtherInformation" name="txtOtherInformation" />
                        </p>
                        <p>
                            <label for="txtStudentPreviousSchool">Vorige school student:</label>
                            <input type="text" id="txtStudentPreviousSchool" name="txtStudentPreviousSchool" />
                        </p>
                        <p>
                            <label for="txtStudentStamnr">Stamnummer student:</label>
                            <input type="text" id="txtStudentStamnr" name="txtStudentStamnr" />
                        </p>
                        <p>
                            <label for="txtParentOccupation">Beroep ouder:</label>
                            <input type="text" id="txtParentOccupation" name="txtParentOccupation" />
                        </p>
 
                    </div>

                    <div id="contactInfo">
                        <h3>Contact informatie</h3>
                        <p>
                            <label for="txtStreet">Straatnaam:</label>
                            <input type="text" id="txtStreet" name="txtStreet" />
                        </p>            
                        <p>
                            <label for="txtHouseNumber">Huisnummer:</label>
                            <input type="text" id="txtHouseNumber" name="txtHouseNumber" />
                        </p>
                        <p>
                            <label for="txtPostCode">Postcode:</label>
                            <input type="text" id="txtPostCode" name="txtPostCode" />
                        </p>
                        <p>
                            <label for="txtCity">Gemeente:</label>
                            <input type="text" id="txtCity" name="txtCity" />
                        </p>
                        <p>
                            <label for="txtCountry">Land:</label>
                            <input type="text" id="txtCountry" name="txtCountry" />
                        </p>
                        <p>
                            <label for="txtEmail">E-mailadres:</label>
                            <input type="text" id="txtEmail" name="txtEmail" />
                        </p>
                        <p>
                            <label for="txtPhone">Telefoonnummer:</label>
                            <input type="text" id="txtPhone" name="txtPhone" />
                        </p>
                        <p>
                            <label for="txtPhone2">Telefoonnummer 2:</label>
                            <input type="text" id="txtPhone2" name="txtPhone2" />
                        </p>
                        <p>
                            <label for="txtMobile">GSM-nummer:</label>
                            <input type="text" id="txtMobile" name="txtMobile" />
                        </p>
                        
                    </div>

                    <div id="accountInfo">
                        <h3>Account informatie</h3>
                        <p>
                            <label for="cbActive">Account actief:</label>
                            <input type="checkbox" id="cbActive" name="cbActive" checked="true" />
                        </p>
                        <p>
                            <label for="txtActiveUntill">Actief tot:</label>
                            <input type="text" id="txtActiveUntill" name="txtActiveUntill" />
                        </p>
                        <p>
                            <label for="txtActiveFrom">Actief vanaf:</label>
                            <input type="text" id="txtActiveFrom" name="txtActiveFrom" />
                        </p>
                        <p>
                            <label for="txtPictureUrl">Foto locatie:</label>
                            <input type="text" id="txtPictureUrl" name="txtPictureUrl" />
                        </p>
                        <p>
                            <label for="cbActive">Homedir:</label>
                            <input type="checkbox" id="cbHomeDir" name="cbHomeDir" checked="true" />
                        </p>
                        <p>
                            <label for="txtHomeDirUrl">Homedir locatie:</label>
                            <input type="text" id="txtHomeDirUrl" name="txtHomeDirUrl" />
                        </p>
                        <p>
                            <label for="cbFtp">FTP-map:</label>
                            <input type="checkbox" id="cbFtp" name="cbFtp" checked="true" />
                        </p>
                        <p>
                            <label for="txtFtpUrl">FTP-map:</label>
                            <input type="text" id="txtFtpUrl" name="txtFtpUrl" />
                        </p>
                    </div>
                    
                </form>
            </div>
               
            <?php
	}
    
}

?>
