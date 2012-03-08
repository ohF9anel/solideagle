<?php

require_once 'data_access/Person.php';

use DataAccess\Person;

if(isset($_POST['login']))
{
        $check = Person::checkValidPersonByCredentials($_POST['txtUsername'], $_POST['txtPassword'], 'admin');

        if (is_object($check))
        {
            $_SESSION['id'] = $check->id;
            $_SESSION['username'] = $check->account_username;
            
            header('Location: index.php');
        }
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<title>Gebruikersbeheer</title>
        <link rel="stylesheet" href="css/screen.css">
</head>
<body>

<h2>Login</h2>

<p>Log in met je schoolaccount</p>

<form action="<?php echo htmlentities($_SERVER['PHP_SELF']); ?>" method="post" id="loginForm">

        <dl>

                <dt><label for="login">Gebruikersnaam:</label></dt>
                <dd>
                        <input type="text" name="txtUsername" id="txtUsername" maxlength="20" size="20" value="" />

                        <span class="message error" id="msglogin"></span>
                </dd>

                <dt><label for="pw">Wachtwoord:</label></dt>
                <dd>
                        <input type="password" name="txtPassword" id="txtPassword" maxlength="20" size="20" value="" />
                        <span class="message error" id="msgpw"></span>
                </dd>

                <dt><label for="autologin">&nbsp;</label></dt>
                <dd>
                        <label for="autologin"><input type="checkbox" class="option" name="autologin" id="autologin" /> herinner me</label>
                </dd>

                <dt>
                        <input type="submit" id="login" name="login" value="Login" />
                </dt>

        </dl>

</form>

</body>
</html>