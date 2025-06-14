<?php
require_once __DIR__ . '/bootstrap.php';

if (session_status() == 2) {
    session_destroy();
    session_start();
    session_create_id();
} else {
    session_start();
    // redirect user to home page if user is logged in
    if (isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true) {
        header('Location: ' . BASE_URL . '/app/index.php');
        exit();
    }
}

?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="id=edge">
    <link rel="stylesheet" href="app/style.css">
    <link rel="stylesheet" href="../template/header.css">
    <link rel="stylesheet" href="lgn.css">
    <script src="https://kit.fontawesome.com/4cb04d37fe.js" crossorigin="anonymous"></script>
    <title>CountFit</title>
</head>

<body>
    <main>
        <form class="login-box" action="includes/login.inc.php" method="post">
            <div class="title">
                <h2>CountFit - Login</h2>
            </div>
            <input type="text" name="mailuid" required placeholder="Benutzername/E-Mail">
            <input type="password" name="pwd" required placeholder="Passwort">
            <div class="login">
                <button class="sign-in" type="submit" name="login-submit">Anmelden</button>
            </div>
            <div class="create-acc">
                Noch Kein Konto? <a href="auth/signup.php">Neues Konto erstellen.</a>
            </div>
        </form>
    </main>
</body>

</html>