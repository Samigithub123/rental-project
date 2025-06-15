<?php
session_start();
require_once "../database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login-form');
    exit();
}

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'] ?? null;
    $start_date = $_POST['start_date'] ?? null;
    $end_date = $_POST['end_date'] ?? null;

    if (!$car_id || !$start_date || !$end_date) {
        $_SESSION['error'] = "Vul alle velden in.";
        header('Location: /car-detail?id=' . $car_id);
        exit();
    }

    try {
        // Get car price
        $stmt = $conn->prepare("SELECT price FROM cars WHERE id = ?");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$car) {
            throw new Exception("Auto niet gevonden.");
        }

        // Calculate total price
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $days = $end->diff($start)->days;
        $total_price = $days * $car['price'];

        // Insert reservation
        $stmt = $conn->prepare("
            INSERT INTO reservations (user_id, car_id, start_date, end_date, total_price, status) 
            VALUES (?, ?, ?, ?, ?, 'pending')
        ");
        $stmt->execute([$_SESSION['id'], $car_id, $start_date, $end_date, $total_price]);

        $_SESSION['success'] = "Je reservering is succesvol geplaatst!";
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