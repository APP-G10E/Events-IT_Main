<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!isset($_POST['festivalName'])) {
    echo json_encode(['error' => 'No festivalName parameter']);
    exit;
}

$festivalName = $conn->real_escape_string($_POST['festivalName']);

$sql = "SELECT festivalId, `IMG-PATH` FROM festival WHERE festivalName LIKE '%$festivalName%'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $festivalId = $row['festivalId'];
    $imgpath = $row['IMG-PATH'];
} else {
    echo json_encode(['error' => 'No festival found with this name']);
    exit;
}

$sql1 = "SELECT sensorId, currentSoundDensity, latitude, longitude FROM sensor WHERE festivalId = '$festivalId'";
$result = $conn->query($sql1);

$sensors = array();
while ($row = $result->fetch_assoc()) {
    $sensors[] = $row;
}

echo json_encode(['sensors' => $sensors, 'imgpath' => $imgpath]);

$conn->close();
?>