<?php
require_once "database/connection.php";

try {
    $stmt = $conn->query("SELECT * FROM cars");
    echo "<pre>";
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        print_r($row);
    }
    echo "</pre>";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
} 