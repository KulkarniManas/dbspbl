<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "JWELERY_MANAGEMENT";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$stmt_status = ""; // Variable to hold the status message

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['u_email'];
    $password = $_POST['u_pass'];

    // Prepare SQL statement
    $stmt = $conn->prepare("SELECT * FROM USER_JWELERY WHERE USER_EMAIL = ? AND USER_PASSWORD = ?");
    $stmt->bind_param("ss", $email, $password); // Note: Password is stored in plaintext
    $stmt->execute();
    $result = $stmt->get_result();

    // Check if user exists
    if ($result->num_rows > 0) {
        // Password is correct
        $stmt_status = "Login successful!";

        // Set cookies or session variables
        setcookie("email", $email, time() + (86400 * 30), "/"); // 86400 seconds = 1 day

        // Redirect to home page
        header("Location: products.html");
        exit(); // Make sure to exit after sending a redirect header
    } else {
        $stmt_status = "Incorrect email or password!";
        echo "<h1>Error:" . $stmt_status . "</h1>";
    }

    // Close statement
    $stmt->close();
}

?>
