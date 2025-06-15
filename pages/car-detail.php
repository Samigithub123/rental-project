<link rel="stylesheet" href="/assets/css/car-detail.css">
<?php require "includes/header.php";
require_once "database/connection.php";

$car_id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
$is_favorite = false;

if (isset($_SESSION['id'])) {
    $stmt = $conn->prepare("SELECT id FROM favorites WHERE user_id = ? AND car_id = ?");
    $stmt->execute([$_SESSION['id'], $car_id]);
    $is_favorite = $stmt->fetch() ? true : false;
}

try {
    $stmt = $conn->prepare("SELECT * FROM cars WHERE id = ? AND is_available = 1");
    $stmt->execute([$car_id]);
    $car = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$car) {
        echo "<div class='container'><h2>Auto niet gevonden</h2></div>";
    } else {
?>
    <link rel="stylesheet" href="/assets/css/car-detail.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <main class="car-detail-page">
        <div class="container">
            <?php if (isset($_SESSION['success'])): ?>
                <div class="success-message">
                    <?= $_SESSION['success'] ?>
                    <?php unset($_SESSION['success']); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message">
                    <?= $_SESSION['error'] ?>
                    <?php unset($_SESSION['error']); ?>
                </div>
            <?php endif; ?>

            <div class="car-detail-content">
                <div class="car-images">
                    <img src="/<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>" class="main-image">
                </div>
                <div class="car-info">
                    <div class="car-header">
                        <h1><?= htmlspecialchars($car['brand']) ?><?= $car['model'] ? ' ' . htmlspecialchars($car['model']) : '' ?></h1>
                        <?php if (isset($_SESSION['id'])): ?>
                            <button class="favorite-btn <?= $is_favorite ? 'active' : '' ?>" data-car-id="<?= $car['id'] ?>">
                                <i class="<?= $is_favorite ? 'fa-solid' : 'fa-regular' ?> fa-heart"></i>
                            </button>
                        <?php endif; ?>
                    </div>
                    <div class="car-category"><?= htmlspecialchars($car['category']) ?></div>
                    
                    <div class="car-specifications">
                        <h2>Specificaties</h2>
                        <div class="specs-grid">
                            <div class="spec-item">
                                <img src="/assets/images/icons/gas-station.svg" alt="Brandstof">
                                <span>Brandstof capaciteit</span>
                                <strong><?= htmlspecialchars($car['fuel_capacity']) ?></strong>
                            </div>
                            <div class="spec-item">
                                <img src="/assets/images/icons/car.svg" alt="Transmissie">
                                <span>Transmissie</span>
                                <strong><?= htmlspecialchars($car['transmission']) ?></strong>
                            </div>
                            <div class="spec-item">
                                <img src="/assets/images/icons/profile-2user.svg" alt="Capaciteit">
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
                        <?php if (isset($_SESSION['id'])): ?>
                            <button class="button-primary" id="openReservationForm">Reserveer nu</button>
                        <?php else: ?>
                            <a href="/login-form" class="button-primary">Login om te reserveren</a>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </main>

    <?php if (isset($_SESSION['id'])): ?>
    <!-- Reservation Modal -->
    <div id="reservationModal" class="modal" style="display: none;">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Reserveer deze auto</h2>
            <?php if (isset($_SESSION['error'])): ?>
                <div class="error-message"><?= $_SESSION['error'] ?></div>
                <?php unset($_SESSION['error']); ?>
            <?php endif; ?>
            <form id="reservationForm" action="/actions/process-reservation.php" method="POST">
                <input type="hidden" name="car_id" value="<?= $car['id'] ?>">
                <div class="form-group">
                    <label for="start_date">Startdatum:</label>
                    <input type="date" id="start_date" name="start_date" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label for="end_date">Einddatum:</label>
                    <input type="date" id="end_date" name="end_date" required min="<?= date('Y-m-d') ?>">
                </div>
                <div class="form-group">
                    <label>Totale prijs:</label>
                    <div id="totalPrice">€0,00</div>
                </div>
                <button type="submit" class="button-primary">Bevestig reservering</button>
            </form>
        </div>
    </div>
    <?php endif; ?>

    <style>
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        background-color: rgba(0,0,0,0.5);
    }

    .modal-content {
        background-color: #fefefe;
        margin: 15% auto;
        padding: 20px;
        border-radius: 8px;
        width: 80%;
        max-width: 500px;
        position: relative;
    }

    .close {
        position: absolute;
        right: 20px;
        top: 10px;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }

    .form-group {
        margin-bottom: 1rem;
    }

    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        color: #1A202C;
    }

    .form-group input {
        width: 100%;
        padding: 0.5rem;
        border: 1px solid #E2E8F0;
        border-radius: 4px;
        font-size: 1rem;
    }

    #totalPrice {
        font-size: 1.5rem;
        font-weight: bold;
        color: #1A202C;
        margin-top: 0.5rem;
    }

    .car-header {
        display: flex;
        align-items: center;
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .favorite-btn {
        background: none;
        border: none;
        cursor: pointer;
        font-size: 1.5rem;
        color: #94a2bc;
        padding: 0.5rem;
        transition: color 0.3s ease;
    }

    .favorite-btn.active {
        color: #f55f5f;
    }

    .favorite-btn:hover {
        transform: scale(1.1);
    }
    </style>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const modal = document.getElementById('reservationModal');
        const btn = document.getElementById('openReservationForm');
        const span = document.getElementsByClassName('close')[0];
        const startDate = document.getElementById('start_date');
        const endDate = document.getElementById('end_date');
        const totalPrice = document.getElementById('totalPrice');
        const dailyPrice = <?= $car['price'] ?>;
        const favoriteBtn = document.querySelector('.favorite-btn');

        if (btn) {
            btn.onclick = function() {
                modal.style.display = "block";
            }
        }

        if (span) {
            span.onclick = function() {
                modal.style.display = "none";
            }
        }

        window.onclick = function(event) {
            if (event.target == modal) {
                modal.style.display = "none";
            }
        }

        function calculateTotalPrice() {
            if (startDate && endDate && startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                const days = Math.ceil((end - start) / (1000 * 60 * 60 * 24));
                if (days > 0) {
                    const total = days * dailyPrice;
                    totalPrice.textContent = '€' + total.toFixed(2).replace('.', ',');
                } else {
                    totalPrice.textContent = '€0,00';
                }
            }
        }

        if (startDate && endDate) {
            startDate.addEventListener('change', calculateTotalPrice);
            endDate.addEventListener('change', calculateTotalPrice);
        }

        if (favoriteBtn) {
            favoriteBtn.addEventListener('click', function() {
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
                        const icon = this.querySelector('i');
                        if (data.action === 'added') {
                            icon.classList.remove('fa-regular');
                            icon.classList.add('fa-solid');
                            this.classList.add('active');
                        } else {
                            icon.classList.remove('fa-solid');
                            icon.classList.add('fa-regular');
                            this.classList.remove('active');
                        }
                    }
                });
            });
        }
    });
    </script>
<?php
    }
} catch(PDOException $e) {
    echo "Error: " . $e->getMessage();
}
require "includes/footer.php";
?>
