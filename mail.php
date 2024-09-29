<?php

$email = $_POST['email']; 
$user_name = $_POST['name'];
if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $to = $email;
    $subject = "Welcome to Task App!";
    $message = "Hello " . $user_name . ", welcome to our Task App!";
    $headers = "From: no-reply@taskapp.com";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "Email successfully sent to $user_name!";
    } else {
        echo "Failed to send email.";
    }
} else {
    echo "Invalid email address.";
}

?>

<html>
<form action="mail.php" method="post">
    Name: <input type="text" name="name"><br>
    Email: <input type="text" name="email"><br>
    <input type="submit" value="Sign Up">
</form>
</html>

