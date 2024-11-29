<?php
include 'connection.php'; // Include database connection

$msg = ''; // For displaying success or error messages

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Retrieve form data
    $first_name = mysqli_real_escape_string($connection, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($connection, $_POST['last_name']);
    $dob = mysqli_real_escape_string($connection, $_POST['dob']);
    $gender = mysqli_real_escape_string($connection, $_POST['gender']);
    $mobile = mysqli_real_escape_string($connection, $_POST['mobile']);
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Check if the email already exists
    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $check_query);

    if ($result && mysqli_num_rows($result) > 0) {
        $msg = "An account with this email already exists. Please log in.";
    } else {
        // Hash the password before storing it
        $hashed_password = password_hash($password, PASSWORD_BCRYPT);

        // Insert user data into the database
        $insert_query = "INSERT INTO users (first_name, last_name, dob, gender, mobile, email, password) 
                         VALUES ('$first_name', '$last_name', '$dob', '$gender', '$mobile', '$email', '$hashed_password')";
        if (mysqli_query($connection, $insert_query)) {
            $msg = "Account created successfully! Please log in.";
        } else {
            $msg = "Error: " . mysqli_error($connection);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rescue Meals - Create an Account</title>
    <link rel="stylesheet" href="loginstyle.css"> <!-- Ensure this file contains necessary styles -->
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #00A36C;
            margin: 0;
            padding: 0;
            color: #fff;
        }
        .container {
            max-width: 400px;
            margin: 50px auto;
            background: #fff;
            color: #000;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0px 4px 10px rgba(0, 0, 0, 0.2);
        }
        h1 {
            text-align: center;
            margin-bottom: 10px;
            color: #00A36C;
        }
        p {
            text-align: center;
            font-size: 14px;
            margin-bottom: 20px;
        }
        .form-group {
            margin-bottom: 15px;
        }
        .form-group label {
            display: block;
            font-size: 14px;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
            width: 100%;
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ccc;
            border-radius: 4px;
        }
        .form-group input[type="date"] {
            color: #666;
        }
        .btn {
            width: 100%;
            padding: 10px;
            background-color: #00A36C;
            color: #fff;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }
        .btn:hover {
            background-color: #008653;
        }
        .login-link {
            text-align: center;
            margin-top: 10px;
            font-size: 14px;
        }
        .login-link a {
            color: #00A36C;
            text-decoration: none;
        }
        .login-link a:hover {
            text-decoration: underline;
        }
        .msg {
            text-align: center;
            color: red;
            font-size: 14px;
            margin-bottom: 10px;
        }
        .msg.success {
            color: green;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Create an Account</h1>
        <p>Join us to donate food or contribute funds</p>

        <!-- Display messages -->
        <?php if (!empty($msg)): ?>
            <p class="msg <?php echo strpos($msg, 'successfully') !== false ? 'success' : ''; ?>">
                <?php echo $msg; ?>
            </p>
        <?php endif; ?>

        <form action="signup.php" method="POST">
            <div class="form-group">
                <label for="firstName">First Name:</label>
                <input type="text" id="firstName" name="first_name" placeholder="Enter your first name" required>
            </div>
            <div class="form-group">
                <label for="lastName">Last Name:</label>
                <input type="text" id="lastName" name="last_name" placeholder="Enter your last name" required>
            </div>
            <div class="form-group">
                <label for="dob">Date of Birth:</label>
                <input type="date" id="dob" name="dob" required>
            </div>
            <div class="form-group">
                <label for="gender">Gender:</label>
                <select id="gender" name="gender" required>
                    <option value="" disabled selected>Select Gender</option>
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                    <option value="Other">Other</option>
                </select>
            </div>
            <div class="form-group">
                <label for="mobile">Mobile Number:</label>
                <input type="tel" id="mobile" name="mobile" pattern="[0-9]{10}" placeholder="Enter your mobile number" required>
            </div>
            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" placeholder="Enter your email" required>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <input type="password" id="password" name="password" placeholder="Enter your password" required>
            </div>
            <button type="submit" class="btn">Sign Up</button>
        </form>

        <p class="login-link">Already have an account? <a href="signin.php">Login</a></p>
    </div>
</body>
</html>
