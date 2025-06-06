<link rel="stylesheet" href="/Rental/assets/css/car-detail.css">
<?php require "includes/header.php";
require_once "database/connection.php";


$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

try {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ? AND is_available = 1");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        echo "<div class='container'><h2>Auto niet gevonden</h2></div>";
    } else {
?>
    <main class="car-detail-page">
        <div class="container">
            <div class="car-detail-content">
                <div class="car-images">
                    <img src="/Rental/<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>" class="main-image">
                </div>
                <div class="car-info">
                    <h1><?= htmlspecialchars($car['brand']) ?><?= $car['model'] ? ' ' . htmlspecialchars($car['model']) : '' ?></h1>
                    <div class="car-category"><?= htmlspecialchars($car['category']) ?></div>
                    
                    <div class="car-specifications">
                        <h2>Specificaties</h2>
                        <div class="specs-grid">
                            <div class="spec-item">
                                <img src="/Rental/assets/images/icons/gas-station.svg" alt="Brandstof">
                                <span>Brandstof capaciteit</span>
                                <strong><?= htmlspecialchars($car['fuel_capacity']) ?></strong>
                            </div>
                            <div class="spec-item">
                                <img src="/Rental/assets/images/icons/car.svg" alt="Transmissie">
                                <span>Transmissie</span>
                                <strong><?= htmlspecialchars($car['transmission']) ?></strong>
                            </div>
                            <div class="spec-item">
                                <img src="/Rental/assets/images/icons/profile-2user.svg" alt="Capaciteit">
                                <span>Capaciteit</span>
                                <strong><?= htmlspecialchars($car['capacity']) ?></strong>
                            </div>
                        </div>
                    </div>

                    <div class="car-description">
                        <h2>Beschrijving</h2>
                        <p><?= nl2br(htmlspecialchars($car['description'])) ?></p>
                    </div>

                    <div class="rental-info">
                        <div class="price-info">
                            <?php if ($car['original_price']): ?>
                                <span class="original-price">€<?= number_format($car['original_price'], 2, ',', '.') ?></span>
                            <?php endif; ?>
                            <span class="current-price">
                                <span class="amount">€<?= number_format($car['price'], 2, ',', '.') ?></span>
                                <span class="period">/ dag</span>
                            </span>
                        </div>
                        <a href="#" class="button-primary">Reserveer nu</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
   

<?php
    }
} catch(PDOException $e) {
    echo "<div class='container'><h2>Er is een fout opgetreden</h2></div>";
}

require "includes/footer.php";
?>
