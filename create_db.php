<?php
$username = "root";
$password = "";

try {
    // Connect to MySQL without selecting a database
    $conn = new PDO("mysql:host=localhost", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $sql = "CREATE DATABASE IF NOT EXISTS rental";
    $conn->exec($sql);
    echo "Database created successfully\n";
    
    // Select the database
    $conn->exec("USE rental");
    
    // Create cars table
    $sql = "CREATE TABLE IF NOT EXISTS `cars` (
        `id` int NOT NULL AUTO_INCREMENT,
        `name` varchar(255) NOT NULL,
        `slug` varchar(255) NOT NULL,
        `type` varchar(50) NOT NULL,
        `price` decimal(10,2) NOT NULL,
        `fuel_capacity` varchar(10) NOT NULL,
        `transmission` varchar(50) NOT NULL,
        `capacity` varchar(50) NOT NULL,
        `description` text,
        `image_url` varchar(255) NOT NULL,
        PRIMARY KEY (`id`),
        UNIQUE KEY `slug` (`slug`)
    )";
    $conn->exec($sql);
    echo "Cars table created successfully\n";
    
    // Check if table is empty
    $stmt = $conn->query("SELECT COUNT(*) FROM cars");
    $count = $stmt->fetchColumn();
    
    if ($count == 0) {
        // Insert car data
        $sql = "INSERT INTO `cars` (`name`, `slug`, `type`, `price`, `fuel_capacity`, `transmission`, `capacity`, `description`, `image_url`) VALUES
            ('Koenigsegg Agera RS', 'koenigsegg-agera-rs', 'Supercar', 450.00, '90l', 'Automaat', '2 Personen', 'Een van de snelste productieauto\'s ter wereld met ongeëvenaarde prestaties en luxe.', 'assets/images/products/Car (0).svg'),
            ('Nissan GTR R35', 'nissan-gtr-r35', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (1).svg'),
            ('Rolls Royce Wraith', 'rolls-royce-wraith', 'Luxe Sedan', 300.00, '85l', 'Automaat', '4 Personen', 'De ultieme luxe cabriolet met ongeëvenaarde elegantie en comfort.', 'assets/images/products/Car (2).svg'),
            ('Nissan GTR R35', 'nissan-gtr-r35-2', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (3).svg');
        $conn->exec($sql);
        echo "Car data inserted successfully\n";
    } else {
        echo "Found $count cars in the database\n";
    }
    
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 