<?php
    session_start();
    $idUsers = $_SESSION['userId'];
    require 'dbh.inc.php';
    
    
    $sql = "SELECT sets.setDate, exercises.exerciseName, sets.setWeight, sets.setReps FROM sets JOIN exercises ON sets.exerciseId = exercises.exerciseId where sets.usersId = ?";
    $stmt = mysqli_stmt_init($conn);

    if (!mysqli_stmt_prepare($stmt, $sql)) {
        header("Location: ../app/progress.php?msg=2210");
        exit();
    }
    else {
        mysqli_stmt_bind_param($stmt, 'i', $idUsers);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);
        if (mysqli_num_rows($result) > 0){
            while ($row = mysqli_fetch_assoc($result)) {
                $date = $row["setDate"];
                $exName = $row["exerciseName"];
                $weight = $row["setWeight"];
                $reps = $row["setReps"];
                echo "
                    <tr>
                        <td>$date</td>
                        <td>$exName</td>
                        <td>$weight</td>
                        <td>$reps</td>
                    </tr>
                ";
            }
        }
        else {
            echo "
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Sie haben keine Daten gespeichert.</td>
                        <td></td>
                    </tr>
                ";
        }

        
    }
?>