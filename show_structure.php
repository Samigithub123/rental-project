<?php
require_once "database/connection.php";

try {
    // Get table structure
    $stmt = $conn->query("DESCRIBE cars");
    echo "<h2>Table Structure:</h2>";
    echo "<pre>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";

    // Get table contents
    $stmt = $conn->query("SELECT * FROM cars");
    echo "<h2>Table Contents:</h2>";
    echo "<pre>";
    while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} 