<?php
    require '../includes/dbh.inc.php';    
    $usersId = $_SESSION['userId'];
    $sql = "SELECT bodypartName, bodyparts.bodypartId FROM trainingsession2bodypart join bodyparts on trainingsession2bodypart.bodypartId = bodyparts.bodypartId where trainingsession2bodypart.tsID = ? AND trainingsession2bodypart.usersId = ?";
    $stmt = mysqli_stmt_init($conn);

    if(!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../app/planer.main.php?error=connectionerror");
        exit();
    }
    
    else {
        $currentts = $_SESSION['currentTsId'];
        $tsid = $_SESSION['trainingsessions'][$currentts][1]; 
        mysqli_stmt_bind_param($stmt, 'ii', $tsid, $usersId);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if(mysqli_num_rows($result) > 0) {
            if (!empty($_SESSION['bodyparts'])) {
                $_SESSION['bodyparts'] = array();
            }
            while ($row = mysqli_fetch_assoc($result)) {
                $bpID = $row['bodypartId'];
                array_push($_SESSION['bodyparts'], array($row['bodypartName'], $row['bodypartId']));
            }
        }
    }
?>