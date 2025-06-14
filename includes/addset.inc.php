<?php 
session_start();
error_reporting(0);
if (!isset($_SESSION['userId'])){
    
    echo "user is not logged in";
    header("Location: ../login.php");
}
else {
    if (!isset($_GET['id']) || !isset($_GET['exid'])) {
        header("Location: ../app/planer.uebung.php?id=$exindex&mgs=150");
    }
    else {
        session_start();
        
        if (isset($_POST['save'])) {
            if(!isset($_POST['weight-input']) || !isset($_POST['reps-input'])) {
                header("Location: ../app/planer.uebung.php?id=$exindex&msg=1550");
            } 
            else {
                require_once '../func/save.inc.php';
                header("Location: ../app/planer.uebung.php?id=$exindex&msg=4040");
            }
        }
        else {
            header("Location: ../app/planer.uebung.php?id=$exindex");
        }
    }
}
?>