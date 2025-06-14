<?php
$servername = "localhost";
$dbUsername = "root";
$dBPassword = "";
$dBName = "countfit";

/* creating connection to db*/
$conn = mysqli_connect($servername, $dbUsername, $dBPassword, $dBName);

$pdo = new PDO("mysql:host=$servername;dbname=$dBName;charset=utf8mb4", $dbUsername, $dBPassword);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
