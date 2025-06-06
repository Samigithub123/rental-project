<?php require "includes/header.php" ?>
<?php require_once "database/connection.php" ?>
<link rel="stylesheet" href="/Rental/assets/css/ons-aanbod.css">

<main class="ons-aanbod">
    <div class="main-flex-container">
        <aside class="filters-sidebar">
            <div class="filters">
                <div class="filter-section">
                    <h2>Type voertuig</h2>
                    <div class="filter-buttons">
                        <button class="filter-btn active" data-type="all">Alle auto's</button>
                        <button class="filter-btn" data-type="regular">Personenauto's</button>
                        <button class="filter-btn" data-type="business">Bedrijfswagens</button>
                    </div>
                </div>
                <div class="filter-section">
                    <h2>Categorie</h2>
                    <div class="category-filters">
                        <?php
                        try {
                            $stmt = $conn->query("SELECT DISTINCT category FROM cars WHERE is_available = 1 ORDER BY category");
                            while ($category = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                                <button class="category-btn" data-category="<?= htmlspecialchars($category['category']) ?>">
                                    <?= htmlspecialchars($category['category']) ?>
                                </button>
                            <?php endwhile;
                        } catch(PDOException $e) {
                            echo "Error: " . $e->getMessage();
                        }
                        ?>
                    </div>
                </div>
            </div>
        </aside>
        <div class="cars-section">
            <h1 class="page-title">Ons Aanbod</h1>
            <div class="cars">
                <?php
                try {
                    $stmt = $conn->query("SELECT * FROM cars WHERE is_available = 1 ORDER BY type, category, brand");
                    while ($car = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <div class="car-details" data-type="<?= htmlspecialchars($car['type']) ?>" data-category="<?= htmlspecialchars($car['category']) ?>">
                            <div class="car-brand">
                                <h3><?= htmlspecialchars($car['brand']) ?><?= $car['model'] ? ' ' . htmlspecialchars($car['model']) : '' ?></h3>
                                <div class="car-type"><?= htmlspecialchars($car['category']) ?></div>
                            </div>
                            <img src="/Rental/<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>">
                            <div class="car-specification">
                                <span><img src="/Rental/assets/images/icons/gas-station.svg" alt=""><?= htmlspecialchars($car['fuel_capacity']) ?></span>
                                <span><img src="/Rental/assets/images/icons/car.svg" alt=""><?= htmlspecialchars($car['transmission']) ?></span>
                                <span><img src="/Rental/assets/images/icons/profile-2user.svg" alt=""><?= htmlspecialchars($car['capacity']) ?></span>
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
        </div>
    </div>
</main>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const categoryBtns = document.querySelectorAll('.category-btn');
    const cars = document.querySelectorAll('.car-details');

    const urlParams = new URLSearchParams(window.location.search);
    const filterType = urlParams.get('filter');

    function filterCars() {
        const activeType = document.querySelector('.filter-btn.active').dataset.type;
        const activeCategories = Array.from(document.querySelectorAll('.category-btn.active'))
            .map(btn => btn.dataset.category);

        cars.forEach(car => {
            const matchesType = activeType === 'all' || car.dataset.type === activeType;
            const matchesCategory = activeCategories.length === 0 || 
                                  activeCategories.includes(car.dataset.category);
            
            car.style.display = matchesType && matchesCategory ? 'flex' : 'none';
        });
    }

    if (filterType === 'business') {
        const businessBtn = document.querySelector('.filter-btn[data-type="business"]');
        if (businessBtn) {
            filterBtns.forEach(b => b.classList.remove('active'));
            businessBtn.classList.add('active');
            filterCars();
        }
    }

    filterBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            filterBtns.forEach(b => b.classList.remove('active'));
            btn.classList.add('active');
            filterCars();
        });
    });

    categoryBtns.forEach(btn => {
        btn.addEventListener('click', () => {
            btn.classList.toggle('active');
            filterCars();
        });
    });
});
</script>

<?php require "includes/footer.php" ?>

