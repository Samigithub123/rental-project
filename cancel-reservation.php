<?php
session_start();
require_once "database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login.php');
    exit();
}

// Annuleren is niet meer mogelijk zonder status kolom
header('Location: /mijn_reserveringen');
exit(); 