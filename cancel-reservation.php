<?php
session_start();
require_once "database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /Rental/login.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation_id'])) {
    $reservation_id = $_POST['reservation_id'];
    $user_id = $_SESSION['id'];

    try {
        // Check if reservation belongs to user
        $stmt = $conn->prepare("SELECT id FROM reservations WHERE id = ? AND user_id = ? AND status = 'pending'");
        $stmt->execute([$reservation_id, $user_id]);
        
        if ($stmt->rowCount() === 0) {
            $_SESSION['error'] = "Deze reservering kan niet worden geannuleerd.";
            header('Location: /Rental/mijn_reserveringen');
            exit();
        }

        // Cancel reservation
        $stmt = $conn->prepare("UPDATE reservations SET status = 'cancelled' WHERE id = ?");
        $stmt->execute([$reservation_id]);

        $_SESSION['success'] = "Je reservering is succesvol geannuleerd.";
    } catch(PDOException $e) {
        $_SESSION['error'] = "Er is een fout opgetreden bij het annuleren van de reservering.";
    }
}

header('Location: /Rental/mijn_reserveringen');
exit(); 