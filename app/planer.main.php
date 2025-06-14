<?php
  session_start();
	error_reporting(0);
	if (!isset($_SESSION['userId'])) { // checking if user is logged in
		header("Location: ../login.php");
	}
  else {
    // getting all the training sessions connected to the user and adding them to the session
    if (empty($_SESSION['trainingsessions'])) {
      $_SESSION['trainingsessions'] = array();
      // gets trainingsession informations from db and saves them into a sessionvariable
      require '../func/getts.inc.php';
    }

    if (isset($_GET['msg'])) {
      if($_GET['msg'] == 4050) {
        // resetting session array and getting training data from db
        $_SESSION['trainingsessions'] = array();
        require '../func/getts.inc.php';
      }
      
    }
    
    // unsetting sessionvariables of bodypart page
    unset($_SESSION['currentTsId']);
    unset($_SESSION['bodyparts']);
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
    <link rel="stylesheet" href="planer.main.css">
    <script type="text/javascript" src="../template/jquery.js"></script>
    <script src="../template/header.js"></script>
    <script src="https://kit.fontawesome.com/4cb04d37fe.js" crossorigin="anonymous"></script>

    <title>CountFit</title>
</head>

<body>
    <header>
      <?php
        // showing navbar
				include '../template/header.php';
			?>
    </header>
    <h4 id="title">Trainingseinheiten</h4>
    <main>
        <div class="cards">
          <?php 
            // showing training sessions
            require '../func/showts.inc.php';
          ?>
        </div>
    </main>

</body>

</html>