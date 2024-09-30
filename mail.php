<?php

$servername = "localhost";
$username = "root"; 
$password = ""; 
$dbname = "taskappdb";

$conn = new mysqli($servername, $username, $password, $dbname);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (?, ?)");
$stmt->bind_param("ss", $user_name, $email);

if ($stmt->execute()) {
    echo "User keyed in successfully";
} else {
    echo "Error: " . $stmt->error;
}

$stmt->close();
$conn->close();


$email = $_POST['email']; 
$user_name = $_POST['name'];

if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $to = $email;
    $subject = "Welcome to Task App!";
    $message = "Hello " . $user_name . ", welcome to Task App!";
    $headers = "From: no-reply@taskapp.com";
    
    if (mail($to, $subject, $message, $headers)) {
        echo "Email sent $user_name!";
    } else {
        echo "Email not sent.";
    }
} else {
    echo "Invalid address.";
}

$sql = "SELECT * FROM users ORDER BY id ASC";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $count = 1;
    while($row = $result->fetch_assoc()) {
        echo $count . ". " . $row['name'] . "<br>";
        $count++;
    }
} else {
    echo "Users not found.";
}


?>

<html>
<form action="mail.php" method="post">
    Name: <input type="text" name="name"><br>
    Email: <input type="text" name="email"><br>
    <input type="submit" value="Sign Up">
</form>
</html>

