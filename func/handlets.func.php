<?php

use CountFit\Models\TrainingSession;

require_once __DIR__ . '/../bootstrap.php';

session_start();

/*error_reporting(E_ALL);
error_reporting(-1);
ini_set('error_reporting', E_ALL);*/

if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
} else {
    if (isset($_POST['ts-submit']) && isset($_POST['ts'])) {

        // TODO: Inputvalidation
        $tsname = mb_strtoupper($_POST['ts'], "UTF-8");

        $trainingSession = new TrainingSession(tsName: $tsname);
        $trainingSession->createTrainingSession();
        header("Location: ../app/planer.main.php?msg=4050");
    } else {
        header("Location: ../includes/addts.inc.php?msg=3050");
        exit();
    }
}
