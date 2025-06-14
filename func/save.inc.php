<?php
    // declaring variables
    $exindex = $_GET['exid'];
    $date = date('d.m.Y'); // getting the current date
    $uid = $_SESSION['userId']; 
    $w = $_POST['weight-input'];
    $r = $_POST['reps-input'];
    $id = $_GET['id'];

    if (!is_numeric($w) || !is_numeric($r)) {
        header("Location: ../app/planer.uebung.php?id=$exindex&msg=1550");
    }
    else {
        if ($w <= 0 && $r <= 0) {
            header("Location: ../app/planer.uebung.php?id=$exindex&msg=1550");
        }
        else if ($w <= 0) {
            header("Location: ../app/planer.uebung.php?id=$exindex&msg=1560");
        }
        else if ($r <= 0) {
            header("Location: ../app/planer.uebung.php?id=$exindex&msg=1570");
        }
        else {
            require '../includes/dbh.inc.php';
            
            $sql = "INSERT INTO sets (setDate, setWeight, setReps, usersId, exerciseId) VALUES (?, ?, ?, ?, ?)";
            $stmt = mysqli_stmt_init($conn);
            if (!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../app/planer.uebung.php?id=$exindex&msg=1580");
                exit();
            }
            else {
                mysqli_stmt_bind_param($stmt, 'sdiii', $date, $w, $r, $uid, $id);
                mysqli_stmt_execute($stmt);
            }
        }
    }
?>