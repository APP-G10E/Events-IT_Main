<?php

// Import Settings
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "app_g10e";

// Create connection without database name
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if database exists
$databaseExists = false;
$result = $conn->query("SHOW DATABASES");
if ($result) {
    while ($row = $result->fetch_assoc()) {
        if ($row['Database'] === $dbname) {
            $databaseExists = true;
            break;
        }
    }
}

if ($databaseExists) {
    echo "Database exists. Dropping and recreating database...\n";

    // Drop the existing database `app_g10e`
    $drop_db_query = "DROP DATABASE $dbname";
    if ($conn->query($drop_db_query) === TRUE) {
        echo "Database dropped successfully.\n";
    } else {
        die("Error dropping database: " . $conn->error);
    }
}

// Create new database `app_g10e` to update
$create_db_query = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($conn->query($create_db_query) === TRUE) {
    echo "Database created successfully.\n";
} else {
    die("Error creating database: " . $conn->error);
}

$conn->select_db($dbname);

// Read the SQL file
$sqlFile = 'app_g10e.sql';
$sqlContent = file_get_contents($sqlFile);

if ($sqlContent === false) {
    die("Could not read file $sqlFile");
}

$sqlQueries = explode(';', $sqlContent);

foreach ($sqlQueries as $query) {
    $trimmedQuery = trim($query);
    if (!empty($trimmedQuery)) {
        if ($conn->query($trimmedQuery) === false) {
            echo "Error executing query: " . $conn->error . "\n";
        }
    }
}

echo "Database import completed successfully.";

$conn->close();

