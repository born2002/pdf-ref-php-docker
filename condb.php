<?php
$servername = "pdfdb";
$username = "root";
$password = "12346";
$database = "myweb";

$conn = mysqli_connect($servername,  $username, $password,  $database);

if (!$conn) {
  die("Connection failed: " . mysqli_connect_error());
}
