<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sensorId = $_POST['sensorId'];
$vote = $_POST['vote'];

// Convert the vote to a numerical value
if ($vote === 'up') {
    $vote = 2;
} else if ($vote === 'down') {
    $vote = 1;
} else {
    $vote = 0; // Default value
}

$sql = "INSERT INTO votingparties (sensorId, vote) VALUES (?, ?)";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    echo "Error preparing statement: " . $conn->error; // Log any errors preparing the statement
    exit;
}

$bind_result = $stmt->bind_param("is", $sensorId, $vote);

if ($bind_result === false) {
    echo "Error binding parameters: " . $stmt->error; // Log any errors binding the parameters
    exit;
}

$execute_result = $stmt->execute();

if ($execute_result === false) {
    echo "Error executing statement: " . $stmt->error; // Log any errors executing the statement
    exit;
}

echo "New record created successfully";

$stmt->close();
$conn->close();
?>