<?php
	session_start();
	error_reporting(0);
    // checks if user is really logged in
	if (!isset($_SESSION['userId'])) {
		header("Location: ../login.php");
	}
	else {
        // checks if user has clicked on a trainingsession to come to this page
        if(!isset($_GET['id']))
        {
            header("Location: planer.main.php");
        }
        else {
            // creates a new sessionvariable to save bodyparts
            if (empty($_SESSION['bodyparts'])) {
                $_SESSION['bodyparts'] = array();
            }
            // saves the index of current trainingsessions-sessionvariable into a new sessionvariable 
            $_SESSION['currentTsId'] = $_GET['id'];
            $i = $_SESSION['currentTsId'];
            // gets bodyparts informations from db and saves them into a sessionvariable
            include '../func/getbp.inc.php';

            if (isset($_GET['msg'])) {
                if($_GET['msg'] == 4050) {
                  // resetting session array and getting training data from db
                  $_SESSION['bodyparts'] = array();
                  require '../func/getbp.inc.php';
                }    
            }
            unset($_SESSION['currentBpId']);
            unset($_SESSION['exercises']);
        }
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
    <link rel="stylesheet" href="planer.bodypart.css">
    <script type="text/javascript" src="../template/jquery.js"></script>
    <script src="../template/header.js"></script>
    <script src="https://kit.fontawesome.com/4cb04d37fe.js" crossorigin="anonymous"></script>
    <title>CountFit</title>
</head>

<body>
    <header>
        <?php
			include '../template/header.php';
		?>
    </header>
    <?php 
        $ts = $_SESSION['trainingsessions'];
        $currentts = $_SESSION['currentTsId'];
        $tsName = $_SESSION['trainingsessions'][$_SESSION['currentTsId']][0];
        echo "<h4 id='title'>$tsName</h4>";
    ?>
    <main>
        <div class="cards">
            <?php 
                require '../func/showbp.inc.php';
            ?>
        </div>
    </main>

</body>

</html>