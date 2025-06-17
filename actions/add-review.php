<?php
session_start();
require_once "../database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login-form');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'] ?? null;
    $comment = $_POST['comment'] ?? null;

    if (!$car_id || !$comment) {
        $_SESSION['error'] = "Vul alle velden in.";
        header('Location: /car-detail?id=' . $car_id);
        exit();
    }

    try {
        $stmt = $conn->prepare("
            INSERT INTO reviews (car_id, user_id, comment) 
            VALUES (?, ?, ?)
        ");
        $stmt->execute([$car_id, $_SESSION['id'], $comment]);

        $_SESSION['success'] = "Je review is succesvol geplaatst!";
        header('Location: /car-detail?id=' . $car_id);
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Er is een fout opgetreden: " . $e->getMessage();
        header('Location: /car-detail?id=' . $car_id);
        exit();
    }
} else {
    // If not POST request, redirect back
    header('Location: /ons-aanbod');
    exit();
} 