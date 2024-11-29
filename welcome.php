<?php
session_start();

if (!isset($_SESSION['first_name'])) {
    header("Location: signin.php");
    exit();
}

// Database connection
$connection = mysqli_connect("localhost", "root", "", "dswproject");

if (!$connection) {
    die("Connection failed: " . mysqli_connect_error());
}

$email = $_SESSION['email'];


$query = "SELECT * FROM food_donations WHERE email = '$email'"; 
$query_run = mysqli_query($connection, $query);

if (!$query_run) {
    die("Query failed: " . mysqli_error($connection));
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="welcome.css">
    <title>Welcome</title>
</head>
<body>
    <h1>Welcome, <?php echo htmlspecialchars($_SESSION['first_name']); ?>!</h1>
    <p>You are logged in with email: <?php echo htmlspecialchars($_SESSION['email']); ?></p>
    
    <h2>Your Donation History</h2>

    <?php
   
    if (mysqli_num_rows($query_run) > 0) {
        
        echo "<table border='1'>
                <tr>
                    <th>Food Name</th>
                    <th>Meal Type</th>
                    <th>Category</th>
                    <th>Quantity</th>
                    <th>Name</th>
                    <th>Phone No</th>
                    <th>District</th>
                    <th>Address</th>
                </tr>";

       
        while ($row = mysqli_fetch_assoc($query_run)) {
            echo "<tr>
                    <td>" . htmlspecialchars($row['food']) . "</td>
                    <td>" . htmlspecialchars($row['type']) . "</td>
                    <td>" . htmlspecialchars($row['category']) . "</td>
                    <td>" . htmlspecialchars($row['quantity']) . "</td>
                    <td>" . htmlspecialchars($row['name']) . "</td>
                    <td>" . htmlspecialchars($row['phoneno']) . "</td>
                    <td>" . htmlspecialchars($row['location']) . "</td>
                    <td>" . htmlspecialchars($row['address']) . "</td>
                </tr>";
        }

        echo "</table>";
    } else {
     
        echo "<p>No donation history found. You can donate food <a href='fooddonateform.php'>here</a>.</p>";
    }
    ?>

    <br>
    <a href="home.html" style="color: blue">Back to Home Page</a>
    <br>
    <a href="signin.php" style="color: red">Logout</a>
</body>
</html>
