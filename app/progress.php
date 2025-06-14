<?php
	session_start();
	error_reporting(0);
	if (!isset($_SESSION['userId'])) {
		header("Location: ../login.php");
	}
  
?>
<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="id=edge">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="../template/headr.css">
    <link rel="stylesheet" href="prg.css">
    <script type="text/javascript" src="../template/jquery.js"></script>
    <script src="../template/header.js"></script>
</head>

<body>
    <header>
        <?php
            include '../template/header.php';
	    ?>
    </header>

    <main>
        <table>
            <tr>
                <th>Date</th>
                <th>&Uuml;bung</th>
                <th>Gewicht</th>
                <th>Wiederholungen</th>
            </tr>
            <?php
            include '../includes/showdat.inc.php';
        ?>
        </table>
    </main>
</body>

</html>