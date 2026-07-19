<?php
// Database connection parameters
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "app_g10e";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if the 'onlyFetch' parameter is set in the POST request
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['onlyFetch'])) {
    // Query to select all customers
    $sql = "SELECT surname, firstName, email, phoneNumber FROM customers";
    $result = $conn->query($sql);

    // Fetch data and store it in an array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Convert the array to a JSON string and echo it out
    echo json_encode($data);
} else {
    // Handle delete request
    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['email'])) {
        $email = $_POST['email'];

        // SQL to delete a record
        $sql = "DELETE FROM customers WHERE email = ?";

        // Prepare and bind
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("s", $email);

        if ($stmt->execute()) {
            $message = "Record deleted successfully";
        } else {
            $message = "Error deleting record: " . $conn->error;
        }

        $stmt->close();
    }

    // Query to select all customers
    $sql = "SELECT surname, firstName, email, phoneNumber FROM customers";
    $result = $conn->query($sql);

    // Fetch data and store it in an array
    $data = array();
    while ($row = $result->fetch_assoc()) {
        $data[] = $row;
    }

    // Convert the array to a JSON string and echo it out
    echo json_encode($data);
}

$conn->close();
?>