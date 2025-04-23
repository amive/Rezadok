<?php
require_once 'config.php'; // Include your database connection

// Path to the SQL file
$sqlFile = __DIR__ . '/database/reza.sql';

// Read the SQL file
$sql = file_get_contents($sqlFile);
if ($sql === false) {
    die("Failed to read the SQL file.");
}

try {
    // Split the SQL file into individual queries
    $queries = explode(";", $sql);

    // Execute each query
    foreach ($queries as $query) {
        $query = trim($query);
        if (!empty($query)) {
            $conn->exec($query);
        }
    }

    echo "Database imported successfully!";
} catch (PDOException $e) {
    die("Error importing database: " . $e->getMessage());
}
?>