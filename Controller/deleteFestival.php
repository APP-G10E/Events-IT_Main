<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['festivalId'])) {
        $festivalId = $conn->real_escape_string($_POST['festivalId']);

        $sql = "DELETE FROM festival WHERE festivalId = '$festivalId'";

        if ($conn->query($sql) === TRUE) {
            echo "Festival deleted successfully";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }
    }
}

$conn->close();
?>