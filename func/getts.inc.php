<?php
require '../includes/dbh.inc.php';
$userId = $_SESSION['userId'];
$sql = "SELECT tsName, users2trainingsession.tsID FROM users2trainingsession join trainingsession on users2trainingsession.tsID = trainingsession.tsID where usersID = ?";
$stmt = mysqli_stmt_init($conn);

if (!mysqli_stmt_prepare($stmt, $sql)) {
    header("Location: ../app/planer.main.php?error=connectionerror");
    exit();
} else {
    mysqli_stmt_bind_param($stmt, 'i', $userId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    if (mysqli_num_rows($result) > 0) {
        while ($row = mysqli_fetch_assoc($result)) {
            array_push($_SESSION['trainingsessions'], array($row['tsName'], $row['tsID']));
        }
    }
}
