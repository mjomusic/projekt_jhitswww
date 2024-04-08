<?php
$servername = "localhost";
$username = "s168789";
$password = "myJDW69sql";
$dbname = "s168789";


$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>