<?php

use CountFit\Models\Exercise;

require_once __DIR__ . '/../bootstrap.php';

session_start();
error_reporting(0);
if (!isset($_SESSION['userId'])) {
    echo "user is not logged in";
    header("Location: ../login.php");
} else {

    session_start();

    if (isset($_POST['save'])) {
        if (!isset($_POST['weight-input']) || !isset($_POST['reps-input'])) {
            header("Location: ../app/planer.uebung.php?id=$exindex&msg=1550");
        } else {
            $exerciseId = $_GET['exid'];
            $exercise = Exercise::getById($exerciseId);
            $bpId = $_SESSION['currentBpId'];
            $weight = floatval($_POST['weight-input']);

            $reps = intval($_POST['reps-input']);

            $set = $exercise->addSet($weight, $reps);
            echo 'Set saved ' . $set . ' </br>';
            header("Location: ../app/planer.uebung.php?id=$bpId&msg=4040");
        }
    } else {
        header("Location: ../app/planer.uebung.php?id=$exindex");
    }
}
