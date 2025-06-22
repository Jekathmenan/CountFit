<?php

use CountFit\Models\Exercise;

session_start();
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
} else {
    if (isset($_POST['ex-submit']) && isset($_POST['ex'])) {
        require_once __DIR__ . '/../bootstrap.php';

        $exName = mb_strtoupper($_POST['ex'], "UTF-8");

        $exercise = new Exercise(name: $exName);
        $created = $exercise->create();
        $currentBp = $_SESSION['currentBpId'];
        header("Location: ../app/planer.uebung.php?id=$currentBp&msg=4050");
    } else {
        header("Location: ../includes/addex.inc.php?msg=3050");
        exit();
    }
}
