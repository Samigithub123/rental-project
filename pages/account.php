<?php 
require "../includes/header.php";
require_once "../database/connection.php";

// Check if user is logged in
if (!isset($_SESSION['id'])) {
    header('Location: /login-form');
    exit();
}

// Get user's reservations
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

// Get user details
try {
    $stmt = $conn->prepare("SELECT email FROM users WHERE id = ?");
    $stmt->execute([$_SESSION['id']]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
} catch(PDOException $e) {
    $error = "Er is een fout opgetreden bij het ophalen van je gegevens.";
}
?>

<link rel="stylesheet" href="/assets/css/account.css">

<div class="account-container">
    <div class="profile-section">
        <div class="profile-photo">
            <?php if(isset($_SESSION['profile_image'])): ?>
                <img src="<?php echo $_SESSION['profile_image']; ?>" alt="Profile Photo">
            <?php else: ?>
                <img src="/assets/images/profil.png" alt="Default Profile Photo">
            <?php endif; ?>
        </div>
        <div class="profile-info">
            <h2>Welkom, <?php echo isset($_SESSION['username']) ? $_SESSION['username'] : 'Gebruiker'; ?></h2>
        </div>
    </div>

    <div class="favorites-section">
        <h2>Mijn Favorieten</h2>
        <div class="favorites-grid">
            <?php
            if (isset($_SESSION['id'])) {
                $stmt = $conn->prepare("
                    SELECT c.* 
                    FROM cars c 
                    INNER JOIN favorites f ON c.id = f.car_id 
                    WHERE f.user_id = ? AND c.is_available = 1
                ");
                $stmt->execute([$_SESSION['id']]);
                $favorites = $stmt->fetchAll(PDO::FETCH_ASSOC);

                if (count($favorites) > 0) {
                    foreach ($favorites as $car) {
                        ?>
                        <div class="car-card">
                            <div class="car-image">
                                <img src="/<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>">
                                <button class="favorite-btn active" data-car-id="<?= $car['id'] ?>">
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </div>
                            <div class="car-info">
                                <h3><?= htmlspecialchars($car['brand']) ?><?= $car['model'] ? ' ' . htmlspecialchars($car['model']) : '' ?></h3>
                                <div class="car-category"><?= htmlspecialchars($car['category']) ?></div>
                                <div class="car-price">
                                    <span class="amount">€<?= number_format($car['price'], 2, ',', '.') ?></span>
                                    <span class="period">/ dag</span>
                                </div>
                                <a href="/car-detail?id=<?= $car['id'] ?>" class="button-secondary">Bekijk details</a>
                            </div>
                        </div>
                        <?php
                    }
                } else {
                    echo '<p class="no-favorites">Je hebt nog geen favoriete auto\'s toegevoegd.</p>';
                }
            }
            ?>
        </div>
    </div>
</div>

<main class="account-page">
    <div class="container">
        <div class="page-header">
            <h1>Mijn Reserveringen</h1>
            <a href="/ons-aanbod" class="button-primary">Nieuwe reservering</a>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="reservations">
            <?php if (empty($reservations)): ?>
                <div class="empty-state">
                    <img src="/assets/images/empty-reservations.svg" alt="Geen reserveringen" class="empty-image">
                    <h2>Nog geen reserveringen</h2>
                    <p>Je hebt nog geen auto's gereserveerd. Bekijk ons aanbod en maak je eerste reservering!</p>
                    <a href="/ons-aanbod" class="button-primary">Bekijk ons aanbod</a>
                </div>
            <?php else: ?>
                <div class="reservation-list">
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservation-card">
                            <div class="reservation-image">
                                <img src="/<?= htmlspecialchars($reservation['image_url']) ?>" alt="<?= htmlspecialchars($reservation['brand']) ?>">
                                <span class="status-badge status-<?= $reservation['status'] ?>">
                                    <?php
                                    switch($reservation['status']) {
                                        case 'pending':
                                            echo 'In afwachting';
                                            break;
                                        case 'confirmed':
                                            echo 'Bevestigd';
                                            break;
                                        case 'cancelled':
                                            echo 'Geannuleerd';
                                            break;
                                        case 'completed':
                                            echo 'Afgerond';
                                            break;
                                    }
                                    ?>
                                </span>
                            </div>
                            <div class="reservation-details">
                                <div class="reservation-header">
                                    <h3><?= htmlspecialchars($reservation['brand']) ?><?= $reservation['model'] ? ' ' . htmlspecialchars($reservation['model']) : '' ?></h3>
                                    <div class="reservation-price">
                                        €<?= number_format($reservation['total_price'], 2, ',', '.') ?>
                                    </div>
                                </div>
                                <div class="reservation-info">
                                    <div class="info-item">
                                        <img src="/assets/images/icons/calendar.svg" alt="Datum">
                                        <div>
                                            <span class="label">Startdatum</span>
                                            <span class="value"><?= date('d-m-Y', strtotime($reservation['start_date'])) ?></span>
                                        </div>
                                    </div>
                                    <div class="info-item">
                                        <img src="/assets/images/icons/calendar.svg" alt="Datum">
                                        <div>
                                            <span class="label">Einddatum</span>
                                            <span class="value"><?= date('d-m-Y', strtotime($reservation['end_date'])) ?></span>
                                        </div>
                                    </div>
                                </div>
                                <?php if ($reservation['status'] === 'pending' || $reservation['status'] === 'confirmed'): ?>
                                    <form action="/actions/cancel-reservation.php" method="POST" class="cancel-form">
                                        <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                        <button type="submit" class="button-secondary" onclick="return confirm('Weet je zeker dat je deze reservering wilt annuleren?')">Annuleren</button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</main>

<style>
.account-page {
    padding: 2rem 0;
    background-color: #f8f9fa;
    min-height: calc(100vh - 200px);
}

.page-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.page-header h1 {
    margin: 0;
    font-size: 2rem;
    color: #1a202c;
}

.reservations {
    background: #fff;
    padding: 2rem;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.05);
}

.empty-state {
    text-align: center;
    padding: 3rem 1rem;
}

.empty-image {
    width: 200px;
    height: 200px;
    margin-bottom: 1.5rem;
}

.empty-state h2 {
    color: #1a202c;
    margin-bottom: 0.5rem;
}

.empty-state p {
    color: #718096;
    margin-bottom: 1.5rem;
}

.reservation-list {
    display: grid;
    gap: 1.5rem;
}

.reservation-card {
    display: grid;
    grid-template-columns: 300px 1fr;
    gap: 2rem;
    padding: 1.5rem;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    background: #fff;
    transition: transform 0.2s, box-shadow 0.2s;
}

.reservation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.reservation-image {
    position: relative;
    border-radius: 8px;
    overflow: hidden;
}

.reservation-image img {
    width: 100%;
    height: 200px;
    object-fit: cover;
}

.status-badge {
    position: absolute;
    top: 1rem;
    right: 1rem;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.875rem;
    font-weight: 500;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.status-pending {
    background: #fff3cd;
    color: #856404;
}

.status-confirmed {
    background: #d4edda;
    color: #155724;
}

.status-cancelled {
    background: #f8d7da;
    color: #721c24;
}

.status-completed {
    background: #e2e3e5;
    color: #383d41;
}

.reservation-details {
    display: flex;
    flex-direction: column;
}

.reservation-header {
    display: flex;
    justify-content: space-between;
    align-items: flex-start;
    margin-bottom: 1.5rem;
}

.reservation-header h3 {
    margin: 0;
    font-size: 1.5rem;
    color: #1a202c;
}

.reservation-price {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
}

.reservation-info {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
    gap: 1.5rem;
    margin-bottom: 1.5rem;
}

.info-item {
    display: flex;
    align-items: center;
    gap: 1rem;
}

.info-item img {
    width: 24px;
    height: 24px;
    opacity: 0.5;
}

.info-item .label {
    display: block;
    font-size: 0.875rem;
    color: #718096;
    margin-bottom: 0.25rem;
}

.info-item .value {
    display: block;
    font-weight: 500;
    color: #2d3748;
}

.cancel-form {
    margin-top: auto;
}

.button-secondary {
    background: #dc3545;
    color: white;
    border: none;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    cursor: pointer;
    font-size: 0.875rem;
    font-weight: 500;
    transition: background-color 0.2s;
}

.button-secondary:hover {
    background: #c82333;
}

.success-message {
    background: #d4edda;
    color: #155724;
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

@media (max-width: 768px) {
    .reservation-card {
        grid-template-columns: 1fr;
    }

    .reservation-image {
        height: 200px;
    }

    .reservation-image img {
        height: 100%;
    }

    .page-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const favoriteBtns = document.querySelectorAll('.favorite-btn');
    
    favoriteBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const carId = this.dataset.carId;
            fetch('/actions/toggle-favorite.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/x-www-form-urlencoded',
                },
                body: 'car_id=' + carId
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    if (data.action === 'removed') {
                        // Remove the entire car card from the favorites section
                        const carCard = this.closest('.car-card');
                        carCard.remove();
                        
                        // If no favorites left, show the "no favorites" message
                        const favoritesGrid = document.querySelector('.favorites-grid');
                        if (favoritesGrid.children.length === 0) {
                            favoritesGrid.innerHTML = '<p class="no-favorites">Je hebt nog geen favoriete auto\'s toegevoegd.</p>';
                        }
                    }
                } else {
                    alert(data.message || 'Er is een fout opgetreden');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Er is een fout opgetreden');
            });
        });
    });
});
</script>

<?php require "../includes/footer.php" ?>



