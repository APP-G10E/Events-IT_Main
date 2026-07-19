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

$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Ajouter un Festival</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;700;900&display=swap" rel="stylesheet">
    <style>

        form {
            margin-top: 150px;
            font-family: 'Inter', sans-serif;
            background-color: #121721;
            color: #fff;
            padding: 30px;
            border-radius: 12px;
            width: 200px;
            justify-self: center;
        }


        label {
            font-weight: bold;
            margin-bottom: 5px;
            display: block;
        }

        input[type="text"], input[type="date"], input[type="submit"] {
            width: 200px;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #05c7e6;
            border-radius: 5px;
            color: #ffffff;
            background: #5d5d5d98;
        }

        input[type="submit"] {
            width: 220px;
            background-color: #00ADEF;
            color: white;
            cursor: pointer;
            border: none;
        }

        input[type="submit"]:hover {
            background-color: #008DB9;
        }

        input::placeholder {
            color: #aaaaaa;
        }
    </style>
</head>

<form action="" method="post">

    <label for="festivalName">Nom du Festival:</label><br>
    <input type="text" id="festivalName" name="festivalName" required><br>

    <label for="beginTime">Heure de début:</label><br>
    <input type="date" id="beginTime" name="beginTime" required><br>

    <label for="endTime">Heure de fin:</label><br>
    <input type="date" id="endTime" name="endTime" required><br>

    <label for="ticketPrice">Prix du billet:</label><br>
    <input type="text" id="ticketPrice" name="ticketPrice" required><br><br>

    <input type="submit" value="Ajouter">
</form>

</html>