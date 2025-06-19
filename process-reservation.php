<?php
session_start();
require_once "database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $car_id = $_POST['car_id'];
    $user_id = $_SESSION['id'];
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    try {
        // Calculate total price
        $stmt = $conn->prepare("SELECT price FROM cars WHERE id = ?");
        $stmt->execute([$car_id]);
        $car = $stmt->fetch(PDO::FETCH_ASSOC);
        
        $start = new DateTime($start_date);
        $end = new DateTime($end_date);
        $days = $start->diff($end)->days;
        $total_price = $days * $car['price'];

        // Check if car is available for these dates
        $stmt = $conn->prepare("
            SELECT COUNT(*) FROM reservations 
            WHERE car_id = ? 
            AND (
                (start_date <= ? AND end_date >= ?) OR
                (start_date <= ? AND end_date >= ?) OR
                (start_date >= ? AND end_date <= ?)
            )
        ");
        $stmt->execute([$car_id, $start_date, $start_date, $end_date, $end_date, $start_date, $end_date]);
        $conflicting_reservations = $stmt->fetchColumn();

        if ($conflicting_reservations > 0) {
            $_SESSION['error'] = "Deze auto is niet beschikbaar voor de geselecteerde datums.";
            header('Location: /car-detail.php?id=' . $car_id);
            exit();
        }

        // Create reservation
        $stmt = $conn->prepare("
            INSERT INTO reservations (car_id, user_id, start_date, end_date, total_price)
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([$car_id, $user_id, $start_date, $end_date, $total_price]);

        $_SESSION['success'] = "Je reservering is succesvol aangemaakt!";
        header('Location: /reservering_bevestiging');
        exit();

    } catch(PDOException $e) {
        $_SESSION['error'] = "Er is een fout opgetreden bij het maken van de reservering.";
        header('Location: /car-detail.php?id=' . $car_id);
        exit();
    }
} else {
    header('Location: /');
    exit();
} 