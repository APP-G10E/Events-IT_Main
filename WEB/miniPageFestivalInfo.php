<?php $servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";
$festivalId = uniqid();
$sensorId = uniqid();
$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $festivalName = $conn->real_escape_string($_POST['festivalName']);
    $beginTime = $conn->real_escape_string($_POST['beginTime']);
    $endTime = $conn->real_escape_string($_POST['endTime']);
    $ticketPrice = $conn->real_escape_string($_POST['ticketPrice']);
    $sql = "INSERT INTO festival (festivalId, festivalName, beginTime, endTime, ticketPrice) VALUES ('$festivalId','$festivalName', '$beginTime', '$endTime', '$ticketPrice')";
    if ($conn->query($sql) === TRUE) {
        echo "New record created successfully";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }
}
$conn->close(); ?>

<!DOCTYPE html>
<html>
<div class="miniPageContent">
    <h1>Ajouter un nouveau festival</h1>
    <form action="" method="post"><label for="festivalName">Nom du Festival:</label><br> <input type="text"
                                                                                                id="festivalName"
                                                                                                name="festivalName"
                                                                                                required><br> <label
                for="beginTime">Heure de début:</label><br> <input type="date" id="beginTime" name="beginTime" required><br>
        <label for="endTime">Heure de fin:</label><br> <input type="date" id="endTime" name="endTime" required><br>
        <label
                for="ticketPrice">Prix du billet:</label><br> <input type="text" id="ticketPrice" name="ticketPrice"
                                                                     required><br><br> <input type="submit"
                                                                                              value="Ajouter">
    </form>
</div>
</html>
<script src="/Controller/lang-select.js"></script>