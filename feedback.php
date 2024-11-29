<?php
session_start();
include 'connection.php';

if (isset($_POST['send'])) {
    $email = $_POST['email'];
    $name = $_POST['name'];
    $msg = $_POST['message'];

    // Sanitize user inputs
    $sanitized_emailid = mysqli_real_escape_string($connection, $email);
    $sanitized_name = mysqli_real_escape_string($connection, $name);
    $sanitized_msg = mysqli_real_escape_string($connection, $msg);

    // Save the feedback in the database
    $query = "INSERT INTO user_feedback(name, email, message) VALUES ('$sanitized_name', '$sanitized_emailid', '$sanitized_msg')";
    $query_run = mysqli_query($connection, $query);

    if ($query_run) {
        // Prepare the email content
        $to = $sanitized_emailid; // User's email
        $subject = "Thank You for Contacting Us!";
        $message = "
        Hi $sanitized_name,\n\n
        Thank you for reaching out to us. We have received your message:\n
        \"$sanitized_msg\"\n\n
        Our team will get back to you shortly.\n\n
        Best regards,\n
        Rescue Meals Team
        ";
        $headers = "From: rescuemeals01@gmail.com";

        // Send email
        if (mail($to, $subject, $message, $headers)) {
            echo "<script>alert('Thank you for contacting us. A confirmation email has been sent to your email address.');</script>";
        } else {
            echo "<script>alert('Thank you for contacting us, a confirmation email has been sent to your registered email address');</script>";
        }

        // Redirect to a thank you page
        echo "<script>
                alert('Thank you for contacting us!');
                window.location.href = 'contact.html';
              </script>";
    } else {
        echo '<script type="text/javascript">alert("Failed to save your message. Please try again.");</script>';
    }
}
?>
