<?php 
<link rel="stylesheet" href="/assets/css/reservering.css">
require "includes/header.php";
require_once "database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login.php?redirect=' . urlencode($_SERVER['REQUEST_URI']));
    exit();
}

try {
    $stmt = $conn->prepare("
        SELECT r.*, c.brand, c.model, c.image_url 
        FROM reservations r 
        JOIN cars c ON r.car_id = c.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC
    ");
    $stmt->execute([$_SESSION['id']]);
    $reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Er is een fout opgetreden bij het ophalen van je reserveringen.";
}
?>

<link rel="stylesheet" href="/assets/css/mijn-reserveringen.css">

<main class="mijn-reserveringen">
    <div class="container">
        <h1>Mijn Reserveringen</h1>

        <?php if (isset($_SESSION['success'])): ?>
            <div class="alert alert-success">
                <?= $_SESSION['success'] ?>
                <?php unset($_SESSION['success']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-error">
                <?= $_SESSION['error'] ?>
                <?php unset($_SESSION['error']); ?>
            </div>
        <?php endif; ?>

        <?php if (isset($error)): ?>
            <div class="alert alert-error">
                <?= $error ?>
            </div>
        <?php endif; ?>

        <?php if (empty($reservations)): ?>
            <div class="no-reservations">
                <p>Je hebt nog geen reserveringen.</p>
                <a href="/ons-aanbod" class="button-primary">Bekijk ons aanbod</a>
            </div>
        <?php else: ?>
            <div class="reservations-grid">
                <?php foreach ($reservations as $reservation): ?>
                    <div class="reservation-card">
                        <div class="car-image">
                            <img src="/<?= htmlspecialchars($reservation['image_url']) ?>" alt="<?= htmlspecialchars($reservation['brand']) ?>">
                        </div>
                        <div class="reservation-details">
                            <h3><?= htmlspecialchars($reservation['brand']) ?><?= $reservation['model'] ? ' ' . htmlspecialchars($reservation['model']) : '' ?></h3>
                            <div class="dates">
                                <p>
                                    <strong>Van:</strong> <?= date('d-m-Y', strtotime($reservation['start_date'])) ?><br>
                                    <strong>Tot:</strong> <?= date('d-m-Y', strtotime($reservation['end_date'])) ?>
                                </p>
                            </div>
                            <div class="price">
                                <strong>Totale prijs:</strong> €<?= number_format($reservation['total_price'], 2, ',', '.') ?>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</main>


<?php require "includes/footer.php" ?> 