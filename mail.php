<?php

// Database connection class
class dbconnect {
    private $connection;

    public function __construct($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name) {
        $this->connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name);
    }

    public function connection($db_type, $db_host, $db_port, $db_user, $db_pass, $db_name) {
        if ($db_port != Null) {
            $db_host .= ":" . $db_port;
        }

        try {
            $this->connection = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
            $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "Connected successfully<br>";
        } catch (PDOException $e) {
            echo "Connection failed: " . $e->getMessage();
            $this->connection = null; 
        }
    }

    public function getConnection() {
        return $this->connection;
    }
}

//Database connection
$db = new dbconnect('PDO', 'localhost', 3308, 'root', '1234', 'taskappdb');
$conn = $db->getConnection();

if ($conn) { // Check if the connection was successful

    //form submission
    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Retrieve user input from form
        $email = $_POST['email'];
        $user_name = $_POST['name'];

        // Insert user data into the database using prepared statements
        $stmt = $conn->prepare("INSERT INTO users (name, email) VALUES (:name, :email)");
        $stmt->bindParam(':name', $user_name);
        $stmt->bindParam(':email', $email);

        if ($stmt->execute()) {
            echo "User inserted successfully!<br>";
        } else {
            echo "Error inserting user.<br>";
        }

        // Validating email
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            // Sending email
            $to = $email;
            $subject = "Welcome to Task App!";
            $message = "Hello " . $user_name . ", welcome to Task App!";
            $headers = "From: no-reply@taskapp.com";

            if (mail($to, $subject, $message, $headers)) {
                echo "Email successfully sent to $user_name!<br>";
            } else {
                echo "Failed to send email.<br>";
            }
        } else {
            echo "Invalid email address.<br>";
        }
    }

    // Fetch and display user list in ascending order (ID)
    $sql = "SELECT * FROM users ORDER BY id ASC";
    $result = $conn->query($sql);

    if ($result->rowCount() > 0) {
        $count = 1;
        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
            echo $count . ". " . $row['name'] . "<br>";
            $count++;
        }
    } else {
        echo "No users found.<br>";
    }

} else {
    echo "Unable to connect to the database. Please check your connection settings.";
}

?>

<html>
<form action="mail.php" method="post">
    Name: <input type="text" name="name"><br>
    Email: <input type="text" name="email"><br>
    <input type="submit" value="Sign Up">
</form>
</html>
