<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['festivalId'])) {
    echo json_encode(['error' => 'No festivalId parameter']);
    exit;
}

$festivalId = $conn->real_escape_string($_POST['festivalId']);

$sql = "SELECT sensorId, currentSoundDensity, latitude, longitude FROM sensor WHERE festivalId = '$festivalId'";
$result = $conn->query($sql);

$sensors = array();
while ($row = $result->fetch_assoc()) {
    $sensors[] = $row;
}

echo json_encode(['sensors' => $sensors]);

$conn->close();
?>