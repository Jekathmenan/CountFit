<?php
	session_start();
	error_reporting(0);
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="id=edge">
    <link rel="stylesheet" href="../app/style.css">
    <link rel="stylesheet" href="../template/header.css">
    <link rel="stylesheet" href="sgnup.css">
    <script src="https://kit.fontawesome.com/4cb04d37fe.js" crossorigin="anonymous"></script>
    <title>CountFit</title>
</head>

<body>
    <main>
        <form class="signup-box" action="../includes/signup.inc.php" method="post">
            <div class="title">
                <h2>CountFit - Registrierung</h2>
                <?php
						if (isset($_GET['error'])) {
							if ($_GET['error'] == "emptyfields") {
								echo "<p class='signup-error'>Bitte f&uuml;llen Sie alle Felder aus!</p>";
							}
							else if ($_GET['error'] == "invalidmailuid") {
								echo "<p class='signup-error'>E-Mail Adresse und Benutzername entsprechen nicht dem korrekten Format!</p>";
							}
							else if ($_GET['error'] == "invalidmail") {
								echo "<p class='signup-error'>E-Mail Adresse entspricht nicht dem korrekten Format!</p>";
							}
							else if ($_GET['error'] == "invaliduid") {
								echo "<p class='signup-error'>Benutzername entspricht nicht dem korrekten Format!</p>";
							}
							else if ($_GET['error'] == "passwordcheck") {
								echo "<p class='signup-error'>Passw&ouml;rter stimmen nicht &uuml;berein!</p>";
							}
							else if ($_GET['error'] == "connectionerr") {
								echo "<p class='signup-error'>Es ist ein Verbindungsfehler aufgetaucht!</p>";
							}
							else if ($_GET['error'] == "usernametaken") {
								echo "<p class='signup-error'>Benutzername ist bereits vergeben!</p>";
							}
						}
						else if(isset($_GET['signup'])) {
							if ($_GET['signup'] == "success"){
								echo "<p class='signup-success'>Konto erfolgreich erstellt!</p>";
							}
						}
					?>
            </div>
            <input type="text" name="uid" required placeholder="Benutzername">
            <input type="text" name="mail" required placeholder="E-Mail">
            <input type="password" name="pwd" required placeholder="Passwort">
            <input type="password" name="pwd-repeat" required placeholder="Passwort wiederholen">
            <div class="login">
                <button class="sign-up" type="submit" name="signup-submit">Konto Erstellen</button>
            </div>
            <div class="sign-in">
                Besitzen Sie bereits ein Konto? <a href="../login.php">Zur Anmeldung.</a>
            </div>
        </form>
    </main>
</body>

</html>