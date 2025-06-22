<?php


use CountFit\Models\TrainingSession;

$trainingsSessions = TrainingSession::getCurrentUsersTrainingSessions();

echo "<div class='card'><a class='link-block' href='../includes/addts.inc.php'><i class='fas fa-plus-circle'></i></a></div>";
foreach ($trainingsSessions as $key => $trainingSession) {
    echo "<div class='card'><a class='link-block' href='../app/planer.bp.php?id=$key&in=$trainingSession->tsId'>$trainingSession->tsName</a></div>";
}
