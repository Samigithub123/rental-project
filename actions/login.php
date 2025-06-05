<?php
session_start();
require_once "../database/connection.php";

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $password = $_POST['password'];

    try {
        $select_user = $conn->prepare("SELECT * FROM account WHERE email = :email");
        $select_user->bindParam(":email", $email);
        $select_user->execute();
        $user = $select_user->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            $_SESSION['id'] = $user['id'];
            $_SESSION['email'] = $user['email'];
            header('Location: /Rental/');
            exit();
        } else {
            $_SESSION['error'] = "Ongeldige e-mail of wachtwoord";
            header('Location: /Rental/login-form');
            exit();
        }
    } catch(PDOException $e) {
        $_SESSION['error'] = "Er is een fout opgetreden bij het inloggen";
        header('Location: /Rental/login-form');
        exit();
    }
} else {
    header('Location: /Rental/login-form');
    exit();
}
