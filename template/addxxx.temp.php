<?php 
    session_start();
    error_reporting(0);
    if (!isset($_SESSION['userId'])) {
		header("Location: ../login.php");
	}
?>

<!DOCTYPE html>
<html lang="de">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="id=edge">
    <link rel="stylesheet" href="../app/style.css">
    <link rel="stylesheet" href="../func/addts.func.css">

    <title>CountFit</title>
</head>

<body>
    <main>