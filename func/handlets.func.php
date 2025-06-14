<?php

use CountFit\Models\TrainingSession;

require_once __DIR__ . '/../bootstrap.php';
session_start();
error_reporting(0);
if (!isset($_SESSION['userId'])) {
    header("Location: ../login.php");
} else {
    if (isset($_POST['ts-submit']) && isset($_POST['ts'])) {
        // connecting to db
        require '../includes/dbh.inc.php';
        $tsname = mb_strtoupper($_POST['ts'], "UTF-8");

        echo $tsname . ' </br>';

        // checking if training session exists in db
        $sql = "SELECT tsID, tsName FROM trainingsession WHERE tsName LIKE ?";
        $stmt = mysqli_stmt_init($conn);
        $usersId = $_SESSION['userId'];

        if (!mysqli_stmt_prepare($stmt, $sql)) {
            header("Location: ../includes/addts.inc.php?msg=2210");
            exit();
        } else {
            mysqli_stmt_bind_param($stmt, "s", $tsname);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);


            $tsexists = false;
            $tsconnected = false;
            $tsid = 0;


            if ($row = mysqli_fetch_assoc($result)) {
                // training session exists in db
                echo 'training session exists in db' . ' </br>';
                $tsexists = true;
                $tsid = $row['tsID'];
                var_dump($row);
                echo '</br>' . $tsid . '</br>';
                $sql = "SELECT * FROM users2trainingsession WHERE tsID = ? AND  usersId = ?";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../includes/addts.inc.php?msg=2210");
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "ii", $tsid, $usersId);
                    mysqli_stmt_execute($stmt);
                    $rs = mysqli_stmt_get_result($stmt);
                    if ($r = mysqli_fetch_assoc($rs)) {
                        echo 'user is already connected to the entered training session' . ' </br>';
                        // user is already connected to the entered training session
                        header("Location: ../includes/addts.inc.php?msg=902");
                    } else {
                        // user is not connected to the entered (existing) trainingsession
                        // connect user to trainingsession

                        try {

                            $pdo = new PDO("mysql:host=localhost;dbname=countfit;charset=utf8mb4", 'root', '');
                            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                            $stmt = $pdo->prepare("INSERT INTO users2trainingsession (usersId, tsId) VALUES (:usersId, :tsId)");
                            echo 'tsId' . $tsid;
                            $stmt->execute([
                                ':usersId' => $usersId,
                                ':tsId' => $tsid
                            ]);
                            echo "Insert successful!";


                            /*
                            echo 'user is not connected to the entered (existing) trainingsession' . ' </br>';
                            $sql = "INSERT INTO users2trainingsession (usersId, tsID) VALUES (?, ?)";
                            $stmt = mysqli_stmt_init($conn);

                            if (!mysqli_stmt_prepare($stmt, $sql)) {
                                echo 'Cannot prepare statement?';
                                header("Location: ../includes/addts.inc.php?msg=2210");
                                exit();
                            } else {
                                echo 'Can prepare statement? </br>';
                                mysqli_stmt_bind_param($stmt, "ii", $usersId, $tsid);
                                echo 'Bound parameter? </br>' . $usersId . ' ' . $tsid;
                                mysqli_stmt_execute($stmt);
                                echo 'Execution failed? </br>';
                                echo 'user is now connected to the trainingsession' . ' </br>';
                                header("Location: ../app/planer.main.php?msg=4050");
                            }*/
                        } catch (PDOException $e) {
                            echo "Execution failed: " . $e->getMessage();
                        }
                    }
                }
            } else { // trainingsession does not exist in db
                // insert new training session
                $sql = "INSERT INTO trainingsession (tsName) VALUES (?)";
                $stmt = mysqli_stmt_init($conn);
                echo 'Creating new Trainingsession. </br>';

                if (!mysqli_stmt_prepare($stmt, $sql)) {
                    header("Location: ../includes/addts.inc.php?msg=2210");
                    exit();
                } else {
                    mysqli_stmt_bind_param($stmt, "s", $tsname);
                    mysqli_stmt_execute($stmt);
                    echo 'Created new Trainingsession. </br>';
                    //header("Location: ../app/planer.main.php?msg=4050");

                    // get tsid of trainingsession
                    $sql = "SELECT tsID FROM trainingsession WHERE tsName LIKE ?";
                    $stmt = mysqli_stmt_init($conn);
                    $tsid = 0;

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../includes/addts.inc.php?msg=2210");
                        exit();
                    } else {
                        mysqli_stmt_bind_param($stmt, "s", $tsname);
                        mysqli_stmt_execute($stmt);
                        $result = mysqli_stmt_get_result($stmt);

                        if ($row = mysqli_fetch_assoc($result)) {
                            $tsid = $row['tsID'];
                        }
                    }

                    // connect user to trainingsession
                    $sql = "INSERT INTO users2trainingsession (usersId, tsID) VALUES (?, ?)";
                    $stmt = mysqli_stmt_init($conn);

                    if (!mysqli_stmt_prepare($stmt, $sql)) {
                        header("Location: ../includes/addts.inc.php?msg=2210");
                        exit();
                    } else {
                        echo 'Connecting new Trainingsession to user. </br>';
                        mysqli_stmt_bind_param($stmt, "ii", $usersId, $tsid);
                        mysqli_stmt_execute($stmt);
                        header("Location: ../app/planer.main.php?msg=4050");
                    }
                }
            }
        }
    } else {
        header("Location: ../includes/addts.inc.php?msg=3050");
        exit();
    }
}
