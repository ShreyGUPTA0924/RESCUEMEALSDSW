db_config.php

<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "rescue_meals");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>