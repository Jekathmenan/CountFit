<?php

use CountFit\Controllers\TrainingSessionController;

require_once __DIR__ . '/../bootstrap.php';

session_start();

if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
} else {
    TrainingSessionController::storeTrainingSession();
}
