<?php
session_start();
include 'connection.php'; // Include the database connection

$msg = ''; // For displaying error messages

if (isset($_POST['sign'])) {
    $email = mysqli_real_escape_string($connection, $_POST['email']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    // Fetch user data
    $sql = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($connection, $sql);

    if ($result && mysqli_num_rows($result) == 1) {
        $row = mysqli_fetch_assoc($result);

        if (password_verify($password, $row['password'])) {
            // Store user information in session variables
            $_SESSION['first_name'] = $row['first_name'];
            $_SESSION['email'] = $row['email'];

            // Redirect to welcome page
            header("Location: welcome.php");
            exit();
        } else {
            $msg = "Invalid password. Please try again.";
        }
    } else {
        $msg = "Account not found. Please sign up.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="loginstyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
</head>
<body>
    <style>
        .uil {
            top: 42%;
        }
    </style>
    <div class="container">
        <div class="regform">
            <form action="signin.php" method="post">
                <p class="logo">Rescue <b style="color:#06C167;">Meals</b></p>
                <p id="heading">Welcome back!</p>

                <!-- Email Field -->
                <div class="input">
                    <input type="email" placeholder="Email address" name="email" value="" required />
                </div>

                <!-- Password Field -->
                <div class="password">
                    <input type="password" placeholder="Password" name="password" id="password" required />
                    <i class="uil uil-eye-slash showHidePw"></i>

                    <!-- Display error message if password is incorrect -->
                    <?php if (!empty($msg)) : ?>
                        <p class="error" style="color: red;"><?php echo $msg; ?></p>
                    <?php endif; ?>
                </div>

                <!-- Sign-In Button -->
                <div class="btn">
                    <button type="submit" name="sign">Sign in</button>
                </div>

                <!-- Redirect to Sign-Up -->
                <div class="signin-up">
                    <p id="signin-up">Don't have an account? <a href="signup.php">Register</a></p>
                </div>
            </form>
        </div>
    </div>
    <script src="login.js"></script>
</body>
</html>
