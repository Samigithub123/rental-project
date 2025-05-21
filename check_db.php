<?php
require_once "database/connection.php";

try {
    // Check if cars table exists
    $stmt = $conn->query("SHOW TABLES LIKE 'cars'");
    if ($stmt->rowCount() == 0) {
        echo "Cars table does not exist. Creating it...\n";
        
        // Create cars table
        $sql = "CREATE TABLE `cars` (
            `id` int NOT NULL AUTO_INCREMENT,
            `name` varchar(255) NOT NULL,
            `slug` varchar(255) NOT NULL,
            `type` varchar(50) NOT NULL,
            `price` decimal(10,2) NOT NULL,
            `gas` varchar(10) NOT NULL,
            `transmission` varchar(50) NOT NULL,
            `seats` varchar(50) NOT NULL,
            `description` text,
            `image` varchar(255) NOT NULL,
            PRIMARY KEY (`id`),
            UNIQUE KEY `slug` (`slug`)
        )";
        $conn->exec($sql);
        echo "Cars table created successfully.\n";
        
        // Insert car data
        $sql = "INSERT INTO `cars` (`name`, `slug`, `type`, `price`, `gas`, `transmission`, `seats`, `description`, `image`) VALUES
            ('Koenigsegg Agera RS', 'koenigsegg-agera-rs', 'Supercar', 450.00, '90l', 'Automaat', '2 Personen', 'Een van de snelste productieauto\'s ter wereld met ongeëvenaarde prestaties en luxe.', 'assets/images/products/Car (0).svg'),
            ('Nissan GTR R35', 'nissan-gtr-r35', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (1).svg'),
            ('Rolls Royce Wraith', 'rolls-royce-wraith', 'Luxe Sedan', 300.00, '85l', 'Automaat', '4 Personen', 'De ultieme luxe cabriolet met ongeëvenaarde elegantie en comfort.', 'assets/images/products/Car (2).svg'),
            ('Nissan GTR R35', 'nissan-gtr-r35-2', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (3).svg'),;
        $conn->exec($sql);
        echo "Car data inserted successfully.\n";
    } else {
        echo "Cars table exists. Checking data...\n";
        
        // Check if there's data in the cars table
        $stmt = $conn->query("SELECT COUNT(*) FROM cars");
        $count = $stmt->fetchColumn();
        
        if ($count == 0) {
            echo "No data in cars table. Inserting data...\n";
            
            // Insert car data
            $sql = "INSERT INTO `cars` (`name`, `slug`, `type`, `price`, `gas`, `transmission`, `seats`, `description`, `image`) VALUES
                ('Koenigsegg Agera RS', 'koenigsegg-agera-rs', 'Supercar', 450.00, '90l', 'Automaat', '2 Personen', 'Een van de snelste productieauto\'s ter wereld met ongeëvenaarde prestaties en luxe.', 'assets/images/products/Car (0).svg'),
                ('Nissan GTR R35', 'nissan-gtr-r35', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (1).svg'),
                ('Rolls Royce Wraith', 'rolls-royce-wraith', 'Luxe Sedan', 300.00, '85l', 'Automaat', '4 Personen', 'De ultieme luxe cabriolet met ongeëvenaarde elegantie en comfort.', 'assets/images/products/Car (2).svg'),
                ('Nissan GTR R35', 'nissan-gtr-r35-2', 'Sportwagen', 249.00, '75l', 'Schakel', '4 Personen', 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.', 'assets/images/products/Car (3).svg'),;
            $conn->exec($sql);
            echo "Car data inserted successfully.\n";
        } else {
            echo "Found $count cars in the database.\n";
            
            // Show first few cars
            $stmt = $conn->query("SELECT * FROM cars LIMIT 3");
            while ($car = $stmt->fetch(PDO::FETCH_ASSOC)) {
                echo "Car: " . $car['name'] . " (ID: " . $car['id'] . ")\n";
            }
        }
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 