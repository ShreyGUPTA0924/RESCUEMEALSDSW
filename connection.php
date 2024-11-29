<?php
$server = "localhost";  // Replace with your database server
$username = "root";     // Replace with your database username
$password = "";         // Replace with your database password
$database = "dswproject";  // Replace with your database name

// Create connection
$connection = mysqli_connect($server, $username, $password, $database);

// Check connection
if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
