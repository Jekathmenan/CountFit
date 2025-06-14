<?php 
    session_start();
    //error_reporting(0);
    if (!isset($_SESSION['userId'])) {
		header("Location: ../login.php");
	}
    else {
        if(isset($_POST['ex-submit']) && isset($_POST['ex'])) {
            // connecting to db
            require '../includes/dbh.inc.php';
            $exName = mb_strtoupper($_POST['ex'], "UTF-8");
            $usersId = $_SESSION['userId'];
            $currentBpId = $_SESSION['currentBpId'];
            $bpId = $_SESSION['bodyparts'][$currentBpId][1];
            
            // checking if exercise exists in db
            $sql = "SELECT exerciseId FROM exercises WHERE exerciseName LIKE ?";
            $stmt = mysqli_stmt_init($conn);
            
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../includes/addex.inc.php?msg=2210");
                exit();
            }
            else 
            {
                mysqli_stmt_bind_param($stmt, "s", $exName);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $exId = 0;
                if($row = mysqli_fetch_assoc($result)) { 
                    // exercise exists in db
                    $exId = $row['exerciseId'];
                    $sql = "SELECT * FROM bodyparts2exercise WHERE bodypartId = ? AND  exerciseId = ? AND usersId = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)) { 
                        header("Location: ../includes/addex.inc.php?msg=2210");
                        exit();
                    }
                    else {
                        mysqli_stmt_bind_param($stmt, "iii", $bpId, $exId, $usersId);
                        mysqli_stmt_execute($stmt);
                        $rs = mysqli_stmt_get_result($stmt);
                        if ($r = mysqli_fetch_assoc($rs)) { 
                            // exercise is already connected to the selected bodypart and user
                            header("Location: ../includes/addex.inc.php?msg=902");
                        }
                        else {
                            // (existing) exercise is not connected to the selected trainingsession and user
                            // connect trainingsession to bodypart
                            $sql = "INSERT INTO bodyparts2exercise (bodypartId, exerciseId, usersId) VALUES (?, ?, ?)";
                            $stmt = mysqli_stmt_init($conn);

                            if(!mysqli_stmt_prepare($stmt, $sql)) {
                                header("Location: ../includes/addex.inc.php?msg=2210");
                                exit();
                            }
                            else {
                                mysqli_stmt_bind_param($stmt, "iii", $bpId, $exId, $usersId);
                                mysqli_stmt_execute($stmt);
                                header("Location: ../app/planer.uebung.php?id=$currentBpId&msg=4050");
                            }
                        }
                    }
                } else { // bodypart does not exist in db
                    // insert new training session
                    echo "exercise does not already exist <br/>";
                    $sql = "INSERT INTO exercises (exerciseName) VALUES (?)";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../includes/addex.inc.php?msg=2210");
                        exit();
                    }
                    else {
                        mysqli_stmt_bind_param($stmt, "s", $exName);
                        mysqli_stmt_execute($stmt);
                        header("Location: ../app/planer.uebung.php?id=$currentBpId&msg=4050");

                        // get exId of Bodypart
                        $sql = "SELECT exerciseId FROM exercises WHERE exerciseName LIKE ?";
                        $stmt = mysqli_stmt_init($conn);
                        $exId = 0;

                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                            header("Location: ../includes/addex.inc.php?msg=2210");
                            exit();
                        }
                        else 
                        {
                            mysqli_stmt_bind_param($stmt, "s", $exName);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            
                            if($row = mysqli_fetch_assoc($result)) { 
                                $exId = $row['exerciseId'];
                            }
                        }

                        // connect user to trainingsession
                        $sql = "INSERT INTO bodyparts2exercise (bodypartId, exerciseId, usersId) VALUES (?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);

                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                            header("Location: ../includes/addex.inc.php?msg=2210");
                            exit();
                        }
                        else {
                            mysqli_stmt_bind_param($stmt, "iii", $bpId, $exId, $usersId);
                            mysqli_stmt_execute($stmt);
                            header("Location: ../app/planer.uebung.php?id=$currentBpId&msg=4050");
                        }
                    }
                }            
            }
        } else {
            header("Location: ../includes/addex.inc.php?msg=3050");
            exit();
        }
    }
?>