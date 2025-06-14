<?php 
    session_start();
    //error_reporting(0);
    if (!isset($_SESSION['userId'])) {
		header("Location: ../login.php");
	}
    else {
        if(isset($_POST['bp-submit']) && isset($_POST['bp'])) {
            // connecting to db
            require '../includes/dbh.inc.php';
            $bpName = mb_strtoupper($_POST['bp'], "UTF-8");
            $usersId = $_SESSION['userId'];
            $currents = $_SESSION['currentTsId'];
            $tsId = $_SESSION['trainingsessions'][$currents][1];
            
            // checking if bodypart exists in db
            $sql = "SELECT bodypartId FROM bodyparts WHERE bodypartName LIKE ?";
            $stmt = mysqli_stmt_init($conn);
            
            if(!mysqli_stmt_prepare($stmt, $sql)) {
                header("Location: ../includes/addbp.inc.php?msg=2210");
                exit();
            }
            else 
            {
                mysqli_stmt_bind_param($stmt, "s", $bpName);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);
                $bpId = 0;
                
                if($row = mysqli_fetch_assoc($result)) { 
                    // bodypart exists in db
                    $bpId = $row['bodypartId'];
                    $sql = "SELECT * FROM trainingsession2bodypart WHERE bodypartId = ? AND  usersId = ? AND tsID = ?";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)) { 
                        header("Location: ../includes/addbp.inc.php?msg=2210");
                        exit();
                    }
                    else {
                        mysqli_stmt_bind_param($stmt, "iii", $bpId, $usersId, $tsId);
                        mysqli_stmt_execute($stmt);
                        $rs = mysqli_stmt_get_result($stmt);
                        if ($r = mysqli_fetch_assoc($rs)) { 
                            // bodypart is already connected to the entered training session and user
                            header("Location: ../includes/addbp.inc.php?msg=902");
                        }
                        else {
                            // (existing) bodypart is not connected to the selected trainingsession and user
                            // connect trainingsession to bodypart
                            $sql = "INSERT INTO trainingsession2bodypart (tsID, bodypartId, usersId) VALUES (?, ?, ?)";
                            $stmt = mysqli_stmt_init($conn);

                            if(!mysqli_stmt_prepare($stmt, $sql)) {
                                header("Location: ../includes/addbp.inc.php?msg=2210");
                                exit();
                            }
                            else {
                                mysqli_stmt_bind_param($stmt, "iii", $tsId,  $bpId, $usersId);
                                mysqli_stmt_execute($stmt);
                                header("Location: ../app/planer.bp.php?id=$currents&in=$tsId&msg=4050");
                            }
                        }
                    }
                } else { // bodypart does not exist in db
                    // insert new training session
                    $sql = "INSERT INTO bodyparts (bodypartName) VALUES (?)";
                    $stmt = mysqli_stmt_init($conn);

                    if(!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../includes/addbp.inc.php?msg=2210");
                        exit();
                    }
                    else {
                        mysqli_stmt_bind_param($stmt, "s", $bpName);
                        mysqli_stmt_execute($stmt);
                        header("Location: ../app/planer.bp.php?id=$currents&in=$tsId&msg=4050");

                        // get bpId of Bodypart
                        $sql = "SELECT bodypartId FROM bodyparts WHERE bodypartName LIKE ?";
                        $stmt = mysqli_stmt_init($conn);
                        $bpId = 0;

                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                            header("Location: ../includes/addbp.inc.php?msg=2210");
                            exit();
                        }
                        else 
                        {
                            mysqli_stmt_bind_param($stmt, "s", $bpName);
                            mysqli_stmt_execute($stmt);
                            $result = mysqli_stmt_get_result($stmt);
                            
                            if($row = mysqli_fetch_assoc($result)) { 
                                $bpId = $row['bodypartId'];
                            }
                        }

                        // connect user to trainingsession
                        $sql = "INSERT INTO trainingsession2bodypart (tsID, bodypartId, usersId) VALUES (?, ?, ?)";
                        $stmt = mysqli_stmt_init($conn);

                        if(!mysqli_stmt_prepare($stmt, $sql)) {
                            header("Location: ../includes/addbp.inc.php?msg=2210");
                            exit();
                        }
                        else {
                            mysqli_stmt_bind_param($stmt, "iii", $tsId,  $bpId, $usersId);
                            mysqli_stmt_execute($stmt);
                            header("Location: ../app/planer.bp.php?id=$currents&in=$tsId&msg=4050");
                        }
                    }
                }            
            }
            
        } else {
            header("Location: ../includes/addbp.inc.php?msg=3050");
            exit();
        }
    }
?>