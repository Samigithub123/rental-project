<?php require "includes/header.php" ?>
    <header>
        <div class="advertorials">
            <div class="advertorial">
                <h2>Hét platform om een auto te huren</h2>
                <p>Snel en eenvoudig een auto huren. Natuurlijk voor een lage prijs.</p>
                <a href="/Rental/ons-aanbod" class="button-primary">Huur nu een auto</a>
                <img src="assets/images/car-rent-header-image-1.png" alt="">
                <img src="assets/images/header-circle-background.svg" alt="" class="background-header-element">
            </div>
            <div class="advertorial">
                <h2>Wij verhuren ook bedrijfswagens</h2>
                <p>Voor een vaste lage prijs met prettig voordelen.</p>
                <a href="/Rental/ons-aanbod?filter=business" class="button-primary">Huur een bedrijfswagen</a>
                <img src="assets/images/car-rent-header-image-2.png" alt="">
                <img src="assets/images/header-block-background.svg" alt="" class="background-header-element">
            </div>
        </div>
    </header>

    <main>
    <h2 class="section-title">Populaire auto's</h2>
    <div class="cars">
        <?php
        require_once "database/connection.php";
        try {
            $stmt = $conn->query("SELECT * FROM cars WHERE type = 'regular' AND is_available = 1 LIMIT 3");
            while ($car = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="car-details">
                    <div class="car-brand">
                        <h3><?= htmlspecialchars($car['brand']) ?><?= $car['model'] ? ' ' . htmlspecialchars($car['model']) : '' ?></h3>
                        <div class="car-type">
                            <?= htmlspecialchars($car['category']) ?>
                        </div>
                    </div>
                    <img src="<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>">
                    <div class="car-specification">
                        <span><img src="assets/images/icons/gas-station.svg" alt=""><?= htmlspecialchars($car['fuel_capacity']) ?></span>
                        <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars($car['transmission']) ?></span>
                        <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= htmlspecialchars($car['capacity']) ?></span>
                    </div>
                    <div class="rent-details">
                        <span>
                            <?php if ($car['original_price']): ?>
                                <span class="original-price">€<?= number_format($car['original_price'], 2, ',', '.') ?></span>
                            <?php endif; ?>
                            <span class="font-weight-bold">€<?= number_format($car['price'], 2, ',', '.') ?></span> / dag
                        </span>
                        <a href="/Rental/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
                    </div>
                </div>
            <?php endwhile;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>
    <h2 class="section-title">Aanbevolen auto's</h2>
    <div class="cars" id="recommendedCars" style="display: none;">
        <?php
        try {
            $stmt = $conn->query("SELECT * FROM cars WHERE type = 'regular' AND is_available = 1 LIMIT 3, 100");
            while ($car = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                <div class="car-details">
                    <div class="car-brand">
                        <h3><?= htmlspecialchars($car['brand']) ?><?= $car['model'] ? ' ' . htmlspecialchars($car['model']) : '' ?></h3>
                        <div class="car-type">
                            <?= htmlspecialchars($car['category']) ?>
                        </div>
                    </div>
                    <img src="<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>">
                    <div class="car-specification">
                        <span><img src="assets/images/icons/gas-station.svg" alt=""><?= htmlspecialchars($car['fuel_capacity']) ?></span>
                        <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars($car['transmission']) ?></span>
                        <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= htmlspecialchars($car['capacity']) ?></span>
                    </div>
                    <div class="rent-details">
                        <span>
                            <?php if ($car['original_price']): ?>
                                <span class="original-price">€<?= number_format($car['original_price'], 2, ',', '.') ?></span>
                            <?php endif; ?>
                            <span class="font-weight-bold">€<?= number_format($car['price'], 2, ',', '.') ?></span> / dag
                        </span>
                        <a href="/Rental/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
                    </div>
                </div>
            <?php endwhile;
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }
        ?>
    </div>
    <div class="show-more">
        <a href="#" class="button-primary" id="showMoreBtn">Toon meer</a>
    </div>
    </main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showMoreBtn = document.getElementById('showMoreBtn');
    const recommendedCars = document.getElementById('recommendedCars');
    const categoryButtons = document.querySelectorAll('.car-type');
    
    showMoreBtn.addEventListener('click', function(e) {
        e.preventDefault();
        if (recommendedCars.style.display === 'none') {
            recommendedCars.style.display = 'grid';
            showMoreBtn.textContent = 'Verberg';
        } else {
            recommendedCars.style.display = 'none';
            showMoreBtn.textContent = 'Toon meer';
        }
    });

});
</script>

<style>
.car-type {
    color: #90A3BF;
    cursor: pointer;
    padding: 4px 8px;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.car-type:hover {
    background-color: #f0f0f0;
}

.car-type.active {
    background-color: #007bff;
    color: white;
}

.original-price {
    display: block;
    color: #999;
    text-decoration: line-through;
    font-size: 0.875rem;
}

.current-price {
    font-size: 1.25rem;
    font-weight: bold;
    color: #333;
}

.advertorial img[src*="car-rent-header-image-2"] {
    max-width: 60%;
    height: 50%;
    object-fit: contain;
}

.button-primary {
    position: relative;
    z-index: 10;
}
</style>

<?php require "includes/footer.php" ?>