<?php
session_start();
require_once "../database/connection.php";

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Je moet ingelogd zijn om favorieten toe te voegen']);
    exit;
}

if (!isset($_POST['car_id'])) {
    echo json_encode(['success' => false, 'message' => 'Geen auto ID opgegeven']);
    exit;
}

$user_id = $_SESSION['id'];
$car_id = (int)$_POST['car_id'];

try {
    // Check if already in favorites
    $stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND car_id = ?");
    $stmt->execute([$user_id, $car_id]);
    $exists = $stmt->fetch();

    if ($exists) {
        // Remove from favorites
        $stmt = $conn->prepare("DELETE FROM favorites WHERE user_id = ? AND car_id = ?");
        $stmt->execute([$user_id, $car_id]);
        echo json_encode(['success' => true, 'action' => 'removed']);
    } else {
        // Add to favorites
        $stmt = $conn->prepare("INSERT INTO favorites (user_id, car_id) VALUES (?, ?)");
        $stmt->execute([$user_id, $car_id]);
        echo json_encode(['success' => true, 'action' => 'added']);
    }
} catch(PDOException $e) {
    echo json_encode(['success' => false, 'message' => 'Er is een fout opgetreden']);
} 