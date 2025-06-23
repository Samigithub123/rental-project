<?php 
require_once __DIR__ . "/../includes/header.php";
require_once __DIR__ . "/../database/connection.php";

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

    <div class="reservations-section">
        <div class="section-header">
            <h2>Mijn Reserveringen</h2>
            <?php if (!empty($reservations)): ?>
                <a href="/ons-aanbod" class="button-primary">Nieuwe reservering</a>
            <?php endif; ?>
        </div>
        
        <?php if (isset($_SESSION['success'])): ?>
            <div class="success-message"><?= $_SESSION['success'] ?></div>
            <?php unset($_SESSION['success']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="error-message"><?= $_SESSION['error'] ?></div>
            <?php unset($_SESSION['error']); ?>
        <?php endif; ?>

        <div class="reservations-grid">
            <?php if (empty($reservations)): ?>
                <div class="empty-state">
                    <h2>Nog geen reserveringen</h2>
                    <p>Je hebt nog geen auto's gereserveerd. Bekijk ons aanbod en maak je eerste reservering!</p>
                    <a href="/ons-aanbod" class="button-primary">Bekijk ons aanbod</a>
                </div>
            <?php else: ?>
                    <?php foreach ($reservations as $reservation): ?>
                        <div class="reservation-card">
                        <div class="car-image">
                                <img src="/<?= htmlspecialchars($reservation['image_url']) ?>" alt="<?= htmlspecialchars($reservation['brand']) ?>">
                            </div>
                        <div class="car-info">
                                    <h3><?= htmlspecialchars($reservation['brand']) ?><?= $reservation['model'] ? ' ' . htmlspecialchars($reservation['model']) : '' ?></h3>
                            <div class="reservation-dates">
                                <div class="date-item">
                                    <i class="fa-regular fa-calendar"></i>
                                    <span>Start: <?= date('d-m-Y', strtotime($reservation['start_date'])) ?></span>
                                </div>
                                <div class="date-item">
                                    <i class="fa-regular fa-calendar"></i>
                                    <span>Eind: <?= date('d-m-Y', strtotime($reservation['end_date'])) ?></span>
                                        </div>
                                    </div>
                            <div class="car-price">
                                <span class="amount">€<?= number_format($reservation['total_price'], 2, ',', '.') ?></span>
                                <span class="period">totaal</span>
                                </div>
                                <form action="/actions/cancel-reservation.php" method="POST" class="cancel-form">
                                    <input type="hidden" name="reservation_id" value="<?= $reservation['id'] ?>">
                                    <button type="submit" class="button-secondary" onclick="return confirm('Weet je zeker dat je deze reservering wilt annuleren?')">Annuleren</button>
                                </form>
                            </div>
                        </div>
                    <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.account-container {
    max-width: 1200px;
    margin: 0 auto;
    padding: 2rem;
}

.profile-section {
    margin-bottom: 3rem;
}

.profile-info h2 {
    margin: 0;
    color: #1a202c;
    text-align: left;
}

.favorites-section,
.reservations-section {
    margin-bottom: 3rem;
}

.section-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 2rem;
}

.section-header h2 {
    margin: 0;
    color: #1a202c;
}

.favorites-grid,
.reservations-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.car-card,
.reservation-card {
    background: #fff;
    border-radius: 12px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    transition: transform 0.2s, box-shadow 0.2s;
}

.car-card:hover,
.reservation-card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 6px rgba(0,0,0,0.1);
}

.car-image {
    position: relative;
    height: 200px;
    overflow: hidden;
}

.car-image img {
    width: 100%;
    height: 100%;
    object-fit: contain;
    background-color: #f8f9fa;
    padding: 1rem;
}

.car-info {
    padding: 1.5rem;
}

.car-info h3 {
    margin: 0 0 1rem 0;
    color: #1a202c;
}

.car-category {
    color: #718096;
    margin-bottom: 1rem;
}

.reservation-dates {
    margin-bottom: 1rem;
}

.date-item {
    display: flex;
    align-items: center;
    gap: 0.5rem;
    color: #718096;
    margin-bottom: 0.5rem;
}

.car-price {
    margin-bottom: 1rem;
}

.car-price .amount {
    font-size: 1.25rem;
    font-weight: 600;
    color: #2d3748;
}

.car-price .period {
    color: #718096;
    margin-left: 0.25rem;
}

.button-primary,
.button-secondary {
    display: inline-block;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 500;
    text-decoration: none;
    transition: background-color 0.2s;
}

.button-primary {
    background: #4299e1;
    color: white;
}

.button-primary:hover {
    background: #3182ce;
}

.button-secondary {
    background: #3563e9;
    color: white;
    border: none;
    cursor: pointer;
}

.button-secondary:hover {
    background: #c82333;
}

.empty-state {
    grid-column: 1 / -1;
    text-align: center;
    padding: 3rem 1rem;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
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

.success-message,
.error-message {
    padding: 1rem;
    border-radius: 6px;
    margin-bottom: 1.5rem;
}

.success-message {
    background: #d4edda;
    color: #155724;
}

.error-message {
    background: #f8d7da;
    color: #721c24;
}

@media (max-width: 768px) {
    .account-container {
        padding: 1rem;
    }

    .section-header {
        flex-direction: column;
        gap: 1rem;
        text-align: center;
    }

    .favorites-grid,
    .reservations-grid {
        grid-template-columns: 1fr;
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

<?php
try {
    require_once __DIR__ . "/../includes/footer.php";
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
?>



