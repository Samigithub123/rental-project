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
    $reservation_id = $_POST['reservation_id'] ?? null;
    $user_id = $_SESSION['id'];

    // Validate input
    if (!$reservation_id) {
        $_SESSION['error'] = "Reservering ID is verplicht";
        header('Location: /pages/account.php');
        exit();
    }

    try {
        // Check if reservation exists and belongs to user
        $stmt = $conn->prepare("
            SELECT id, status 
            FROM reservations 
            WHERE id = ? AND user_id = ?
        ");
        $stmt->execute([$reservation_id, $user_id]);
        $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$reservation) {
            throw new Exception("Reservering niet gevonden");
        }

        // Check if reservation can be cancelled
        if ($reservation['status'] !== 'pending' && $reservation['status'] !== 'confirmed') {
            throw new Exception("Deze reservering kan niet meer worden geannuleerd");
        }

        // Update reservation status
        $stmt = $conn->prepare("
            UPDATE reservations 
            SET status = 'cancelled' 
            WHERE id = ? AND user_id = ?
        ");
        
        $stmt->execute([$reservation_id, $user_id]);

        $_SESSION['success'] = "Reservering succesvol geannuleerd";
        header('Location: /pages/account.php');
        exit();

    } catch (Exception $e) {
        $_SESSION['error'] = "Er is een fout opgetreden: " . $e->getMessage();
        header('Location: /pages/account.php');
        exit();
    }
} else {
    // If not POST request, redirect back
    header('Location: /pages/account.php');
    exit();
} 