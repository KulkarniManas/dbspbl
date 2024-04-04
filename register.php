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
        $name = $_POST['u_name'];
        $password = $_POST['u_pass'];
        $confirm_password = $_POST['uc_pass'];

        // Check if passwords match
        if ($password !== $confirm_password) {
            $stmt_status = "Passwords do not match";
        } else {
            // Prepare SQL statement
            $stmt = $conn->prepare("INSERT INTO USER_JWELERY (USER_EMAIL, USER_PASSWORD) VALUES (?, ?)");
            $stmt->bind_param("ss", $email, $password); // Storing password directly

            // Execute the statement
            if ($stmt->execute()) {
                $stmt_status = "Registration successful!";

                // Set cookies
                setcookie("email", $email, time() + (86400 * 30), "/"); // 86400 seconds = 1 day
                setcookie("name", $name, time() + (86400 * 30), "/"); // 86400 seconds = 1 day
                setcookie("password", $password, time() + (86400 * 30), "/");

                // Redirect to home page
                header("Location: home.html");
                exit(); // Make sure to exit after sending a redirect header
            } else {
                $stmt_status = "Error: " . $stmt->error;
            }

            // Close statement
            $stmt->close();
        }
    }
?>
