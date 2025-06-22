<?php

use CountFit\Models\Bodypart;

session_start();
require_once __DIR__ . '/../bootstrap.php';
error_reporting(0);
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
} else {
    if (isset($_POST['bp-submit']) && isset($_POST['bp'])) {
        $bpName = mb_strtoupper($_POST['bp'], "UTF-8");
        $bp = new Bodypart(bodypartName: $bpName);

        $bp->createBodypart();

        $usersId = $_SESSION['userId'];
        $currents = $_SESSION['currentTsId'];
        $tsId = $_SESSION['trainingsessions'][$currents][1];
        header("Location: ../app/planer.bp.php?id=$currents&in=$tsId&msg=4050");
    } else {
        header("Location: ../includes/addbp.inc.php?msg=3050");
        exit();
    }
}
