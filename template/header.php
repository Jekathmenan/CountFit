<?php
	include '../auth/session.php';
	if (!isset($_SESSION['userId'])) {

		header("Location: ../login.php");
	}
?>
<nav>
    <ul class="menu">
        <li class="logo"><a href="../app/index.php">CountFit</a> </li>
        <li class="item"><a href="../app/planer.main.php">Trainingsplan</a></li>
        <li class="item"><a href="../app/progress.php">Fortschritt</a></li>
        <li class="item button">
            <form class="" action="../includes/logout.inc.php" method="post">
                <button class="sign-out" type="submit" name="logout-submit">Abmelden</button>
            </form>
        </li>
        <li class="toggle"><span class="bars"></span></li>
    </ul>
</nav>