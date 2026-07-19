<?php
header('Content-Type: application/json');

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    echo json_encode(['success' => false, 'message' => 'Database connection failed']);
    exit();
}

// Get customerId and loginExpireTime from POST data
$customerId = $_POST['customerId'];
$loginExpireTime = $_POST['loginExpireTime'];

// Get selected festivals from POST data
$selectedFestivals = $_POST['festival'];

$response = ['success' => true, 'customerId' => $customerId, 'loginExpireTime' => $loginExpireTime, 'alreadyAssigned' => false];

// Insert selected festivals into customerfestivals table
if (!empty($selectedFestivals)) {
    foreach ($selectedFestivals as $festivalId) {
        // Check if the entry already exists
        $checkSql = "SELECT * FROM customerfestivals WHERE customerId = '$customerId' AND festivalId = '$festivalId'";
        $checkResult = $conn->query($checkSql);

        if ($checkResult->num_rows == 0) {
            // If not exists, then insert
            $insertSql = "INSERT INTO customerfestivals (customerId, festivalId) VALUES ('$customerId', '$festivalId')";
            if ($conn->query($insertSql) !== TRUE) {
                $response['success'] = false;
                $response['message'] = "Error: " . $insertSql . "<br>" . $conn->error;
            }
        } else {
            $response['alreadyAssigned'] = true;
        }
    }
} else {
    $response['success'] = false;
    $response['message'] = 'No festivals selected.';
}

$conn->close();

// Return JSON response
echo json_encode($response);
exit;
?>
