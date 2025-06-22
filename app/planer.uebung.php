<?php

use CountFit\Models\Bodypart;

session_start();
error_reporting(0);
if (!isset($_SESSION['userId'])) {
  header("Location: ../login.php");
} else {
  require_once __DIR__ . '/../bootstrap.php';
  if (!isset($_GET['id'])) {
    if (!empty($_SESSION['currentTsId'])) {
      $trainingsessionId = $_SESSION['currentTsId'];
      header("Location: planer.bp.php?id=$trainingsessionId");
    } else {
      header("Location: planer.main.php");
    }
  } else {
    $_SESSION['currentBpId'] = $_GET['id'];
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
  <link rel="stylesheet" href="planer.uebg.css">
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

  if (isset($_GET['msg'])) {
    $code = $_GET['msg'];
    if ($code == 4040) {
      echo "
            <div class='success-msg'>
              <p>Der ausgewählte Satz wurde erfolgreich gespeichert!</p>
            </div>
          ";
    } else if ($code == 1550) {
      echo "
            <div class='error-msg'>
              <p>Gewicht und Wiederholung dürfen nicht 0 sein!</p>
            </div>
          ";
    } else if ($code == 1560) {
      echo "
            <div class='error-msg'>
              <p>Gewicht darf nicht 0 sein!</p>
            </div>
          ";
    } else if ($code == 1570) {
      echo "
            <div class='error-msg'>
              <p>Wiederholung darf nicht 0 sein!</p>
            </div>
          ";
    } else if ($code == 1580) {
      echo "
            <div class='error-msg'>
              <p>Ein Fehler ist aufgetaucht! Der ausgewählte Satz konnte nicht gespeichert werden!</p>
            </div>
          ";
    }
  }
  ?>

  <main>
    <?php
    echo '<h4 id="title" style="margin-left: 5px">' . Bodypart::getById($_SESSION['currentBpId'])->bodypartName . '</h4>';
    ?>
    <div class="cards">
      <?php
      require '../func/showuebg.inc.php';
      ?>
    </div>
  </main>

</body>

</html>