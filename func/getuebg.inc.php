<?php
    require '../includes/dbh.inc.php';
    $userId = $_SESSION['userId'];
    $sql = "SELECT exerciseName, exercises.exerciseId FROM bodyparts2exercise join exercises on bodyparts2exercise.exerciseId = exercises.exerciseId where bodyparts2exercise.bodypartID = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../app/planer.main.php?error=connectionerror");
        exit();
    }
    else {
        
        $bpid = $_SESSION['bodyparts'][$_SESSION['currentBpId']][1];
        mysqli_stmt_bind_param($stmt, 'i', $bpid);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            if (!empty($_SESSION['exercises'])) {
                $_SESSION['exercises'] = array();
            }
            while ($row = mysqli_fetch_assoc($result)) {
                array_push($_SESSION['exercises'], array($row['exerciseName'], $row['exerciseId']));
            }
        }
    }
?>