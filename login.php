<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "techbulletin";

// Retrieve form input values
$fname = $_POST['fname'];
$minitial = $_POST['minitial'];
$lname = $_POST['lname'];
$email = $_POST['email'];
$password = $_POST['password'];
$username = $_POST['username'];
$bio = $_POST['bio'];
$studNumber = $_POST['studNumber'];

// Create a database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare and execute the SQL query
$stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND password = ?");
$stmt->bind_param("ss", $email, $password);
$stmt->execute();

// Fetch the result
$result = $stmt->get_result();

// Check if the login is successful
if ($result->num_rows > 0) {
    // Login successful
    echo "Login successful!";
} else {
    // Login failed
    echo "Invalid credentials. Please try again.";
}

// Close the database connection
$stmt->close();
$conn->close();
?>
