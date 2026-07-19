<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['festivalId'])) {
    $festivalId = $conn->real_escape_string($_POST['festivalId']);
    $festivalName = $conn->real_escape_string($_POST['festivalName']);
    $beginTime = $conn->real_escape_string($_POST['beginTime']);
    $endTime = $conn->real_escape_string($_POST['endTime']);
    $ticketPrice = $conn->real_escape_string($_POST['ticketPrice']);

    $sql = "UPDATE festival SET festivalName='$festivalName', beginTime='$beginTime', endTime='$endTime', ticketPrice='$ticketPrice' WHERE festivalId='$festivalId'";

    if ($conn->query($sql) === TRUE) {
        echo "Festival updated successfully";
    } else {
        echo "Error updating festival: " . $conn->error;
    }
}

$sql = "SELECT * FROM festival";
$result = $conn->query($sql);

$festivals = array();
while ($row = $result->fetch_assoc()) {
    $festivals[] = $row;
}

echo json_encode($festivals);

$conn->close();
?>