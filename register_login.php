<?php   
$conn = new mysqli("localhost", "root", "", "rescue_meals");
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action']; 
    $username = trim($_POST['username']);

    if ($action === 'register') {
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone']);
        $password = trim($_POST['password']);

        // Validate email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            die("Invalid email format.");
        }

        // Validate phone
        if (!preg_match('/^\d{10}$/', $phone)) {
            die("Phone number must contain exactly 10 digits.");
        }

        // Check if username already exists
        $checkUser = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $checkUser->bind_param("s", $username);
        $checkUser->execute();
        $result = $checkUser->get_result();
        if ($result->num_rows > 0) {
            die("Username already taken. Please choose another.");
        }

        // Insert new user
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, email, phone, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $email, $phone, $hashedPassword);

        if ($stmt->execute()) {
            echo "Registration successful. Please log in.";
        } else {
            echo "Error: " . $conn->error;
        }
    } elseif ($action === 'login') {
        $password = trim($_POST['password']);

        // Check if user exists
        $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
        $stmt->bind_param("s", $username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows === 0) {
            die("Username not found. Please register first.");
        }

        $user = $result->fetch_assoc();
        // Verify password
        if (password_verify($password, $user['password'])) {
            echo "Login successful. Welcome, " . htmlspecialchars($username) . "!";
        } else {
            die("Incorrect password. Please try again.");
        }
    }
}
?>