<?php 
require "includes/header.php";
require_once "database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login.php');
    exit();
}

// Get the latest reservation for this user
try {
    $stmt = $conn->prepare("
        SELECT r.*, c.brand, c.model, c.image_url, c.category
        FROM reservations r 
        JOIN cars c ON r.car_id = c.id 
        WHERE r.user_id = ? 
        ORDER BY r.created_at DESC 
        LIMIT 1
    ");
    $stmt->execute([$_SESSION['id']]);
    $reservation = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$reservation) {
        header('Location: /mijn_reserveringen');
        exit();
    }
} catch(PDOException $e) {
    header('Location: /mijn_reserveringen');
    exit();
}
?>

<link rel="stylesheet" href="/assets/css/reservering.css">

<main class="reservering-bevestiging">
    <div class="container">
        <div class="confirmation-card">
            <div class="confirmation-header">
                <div class="success-icon">
                    <svg width="48" height="48" viewBox="0 0 48 48" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <circle cx="24" cy="24" r="24" fill="#C6F6D5"/>
                        <path d="M32 16L20 28L16 24" stroke="#2F855A" stroke-width="3" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                </div>
                <h1>Reservering Bevestigd!</h1>
                <p>Bedankt voor je reservering. Hier zijn de details van je boeking:</p>
            </div>

            <div class="reservation-details">
                <div class="car-info">
                    <div class="car-image">
                        <img src="/<?= htmlspecialchars($reservation['image_url']) ?>" alt="<?= htmlspecialchars($reservation['brand']) ?>">
                    </div>
                    <div class="car-details">
                        <h2><?= htmlspecialchars($reservation['brand']) ?><?= $reservation['model'] ? ' ' . htmlspecialchars($reservation['model']) : '' ?></h2>
                        <span class="car-category"><?= htmlspecialchars($reservation['category']) ?></span>
                    </div>
                </div>

                <div class="booking-details">
                    <div class="detail-group">
                        <h3>Reserveringsnummer</h3>
                        <p>#<?= str_pad($reservation['id'], 6, '0', STR_PAD_LEFT) ?></p>
                    </div>
                    <div class="detail-group">
                        <h3>Ophaaldatum</h3>
                        <p><?= date('d-m-Y', strtotime($reservation['start_date'])) ?></p>
                    </div>
                    <div class="detail-group">
                        <h3>Retourdatum</h3>
                        <p><?= date('d-m-Y', strtotime($reservation['end_date'])) ?></p>
                    </div>
                    <div class="detail-group">
                        <h3>Totale prijs</h3>
                        <p class="price">€<?= number_format($reservation['total_price'], 2, ',', '.') ?></p>
                    </div>
                </div>
            </div>

            <div class="confirmation-actions">
                <a href="/mijn_reserveringen" class="button-primary">Bekijk al mijn reserveringen</a>
                <a href="/ons-aanbod" class="button-secondary">Bekijk meer auto's</a>
            </div>
        </div>
    </div>
</main>

<style>
.reservering-bevestiging {
    padding: 3rem 0;
    background: #F6F7F9;
    min-height: calc(100vh - 200px);
}

.container {
    max-width: 800px;
    margin: 0 auto;
    padding: 0 1rem;
}

.confirmation-card {
    background: white;
    border-radius: 12px;
    padding: 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
}

.confirmation-header {
    text-align: center;
    margin-bottom: 2rem;
}

.success-icon {
    margin-bottom: 1rem;
}

.confirmation-header h1 {
    color: #1A202C;
    margin-bottom: 0.5rem;
}

.confirmation-header p {
    color: #596780;
}

.reservation-details {
    margin: 2rem 0;
    padding: 2rem;
    background: #F6F7F9;
    border-radius: 8px;
}

.car-info {
    display: flex;
    gap: 2rem;
    margin-bottom: 2rem;
    padding-bottom: 2rem;
    border-bottom: 1px solid #E2E8F0;
}

.car-image {
    width: 200px;
    height: 120px;
    background: #3563E9;
    border-radius: 8px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.car-image img {
    max-width: 100%;
    max-height: 100%;
    object-fit: contain;
}

.car-details h2 {
    margin: 0 0 0.5rem 0;
    color: #1A202C;
}

.car-category {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #E2E8F0;
    border-radius: 4px;
    color: #4A5568;
    font-size: 0.875rem;
}

.booking-details {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
}

.detail-group h3 {
    color: #4A5568;
    font-size: 0.875rem;
    margin: 0 0 0.5rem 0;
}

.detail-group p {
    color: #1A202C;
    font-size: 1.125rem;
    margin: 0;
}

.detail-group .price {
    color: #3563E9;
    font-weight: bold;
    font-size: 1.25rem;
}

.confirmation-actions {
    display: flex;
    gap: 1rem;
    justify-content: center;
    margin-top: 2rem;
}

.button-secondary {
    background: #E2E8F0;
    color: #1A202C;
    padding: 0.75rem 1.5rem;
    border-radius: 4px;
    text-decoration: none;
    font-weight: 500;
    transition: background-color 0.2s;
}

.button-secondary:hover {
    background: #CBD5E0;
}

@media (max-width: 768px) {
    .car-info {
        flex-direction: column;
        align-items: center;
        text-align: center;
    }

    .confirmation-actions {
        flex-direction: column;
    }

    .button-primary, .button-secondary {
        width: 100%;
        text-align: center;
    }
}
</style>

<?php require "includes/footer.php" ?> 