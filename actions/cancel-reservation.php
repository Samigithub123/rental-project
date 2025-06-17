<?php
session_start();
require_once "../database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login-form');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $reservation_id = $_POST['reservation_id'] ?? null;

    if (!$reservation_id) {
        $_SESSION['error'] = "Reservering ID is niet gevonden.";
        header('Location: /account');
        exit();
    }

    try {
        // Check if the reservation belongs to the logged-in user
        $stmt = $conn->prepare("SELECT id FROM reservations WHERE id = ? AND user_id = ?");
        $stmt->execute([$reservation_id, $_SESSION['id']]);
        
        if (!$stmt->fetch()) {
            $_SESSION['error'] = "Deze reservering bestaat niet of is niet van jou.";
            header('Location: /account');
            exit();
        }

        // Delete the reservation
        $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ? AND user_id = ?");
        $stmt->execute([$reservation_id, $_SESSION['id']]);

        $_SESSION['success'] = "Je reservering is succesvol geannuleerd!";
        header('Location: /account');
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Er is een fout opgetreden: " . $e->getMessage();
        header('Location: /account');
        exit();
    }
} else {
    // If not POST request, redirect back
    header('Location: /account');
    exit();
} 