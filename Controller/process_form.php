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
    if (isset($_POST['user_name']) && isset($_POST['user_Fname']) && isset($_POST['user_email']) && isset($_POST['demande'])) {
        $userName = $_POST['user_name'];
        $userFname = $_POST['user_Fname'];
        $userEmail = $_POST['user_email'];
        $demande = $_POST['demande'];

        // Check if the entry already exists
        $checkSql = "SELECT * FROM contact_us WHERE firstName = ? AND surname = ? AND email = ? AND msg = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ssss", $userFname, $userName, $userEmail, $demande);
        $checkStmt->execute();
        $checkResult = $checkStmt->get_result();

        if ($checkResult->num_rows > 0) {
            // Entry already exists
            $response = ['success' => false, 'message' => 'Duplicate entry'];
            echo json_encode($response);
        } else {
            // Entry does not exist, insert it
            $sql = "INSERT INTO contact_us (firstName, surname, email, msg) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssss", $userFname, $userName, $userEmail, $demande);

            $response = [];
            if ($stmt->execute()) {
                $response['success'] = true;
            } else {
                $response['success'] = false;
            }

            echo json_encode($response);
        }

    } else {
        $response = ['success' => false, 'message' => 'POST data does not exist'];
        echo json_encode($response);
    }
}
?>