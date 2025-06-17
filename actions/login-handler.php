<?php
session_start();
require_once "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $conn->prepare("SELECT * FROM account WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: /account');
            exit();
        } else {
            $_SESSION['error'] = "Ongeldig emailadres of wachtwoord";
            header('Location: /login-form');
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Er is een fout opgetreden bij het inloggen";
        header('Location: /login-form');
        exit();
    }
} else {
    header('Location: /login-form');
    exit();
} 