<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";
$festivalId = uniqid();
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$messages = array();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['festivalName'])) {
        $festivalName = $conn->real_escape_string($_POST['festivalName']);
        $beginTime = $conn->real_escape_string($_POST['beginTime']);
        $endTime = $conn->real_escape_string($_POST['endTime']);
        $ticketPrice = $conn->real_escape_string($_POST['ticketPrice']);

        $target_dir = "../Assets/";
        $festivalImageName = basename($_FILES["festivalImage"]["name"]);
        $target_file = $target_dir . $festivalImageName;

        if (move_uploaded_file($_FILES["festivalImage"]["tmp_name"], $target_file)) {
            $messages[] = "The file " . $festivalImageName . " has been uploaded.";
        } else {
            $messages[] = "Sorry, there was an error uploading your file.";
        }

        $sql = "INSERT INTO festival (festivalId, festivalName, beginTime, endTime, ticketPrice, `IMG-PATH`) VALUES ('$festivalId','$festivalName', '$beginTime', '$endTime', '$ticketPrice', '$target_file')";

        if ($conn->query($sql) === TRUE) {
            $messages[] = "Festival added successfully";
        } else {
            $messages[] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}
echo json_encode($messages);

$conn->close();
?>