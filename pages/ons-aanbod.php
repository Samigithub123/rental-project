<?php require "includes/header.php" ?>
<?php require_once "database/connection.php" ?>

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
            <div class="cars-grid">
                <?php
                try {
                    $stmt = $conn->query("SELECT * FROM cars WHERE is_available = 1 ORDER BY type, category, brand");
                    while ($car = $stmt->fetch(PDO::FETCH_ASSOC)) : ?>
                        <div class="car-card" data-type="<?= htmlspecialchars($car['type']) ?>" data-category="<?= htmlspecialchars($car['category']) ?>">
                            <div class="car-image">
                                <img src="<?= htmlspecialchars($car['image_url']) ?>" alt="<?= htmlspecialchars($car['brand']) ?>">
                            </div>
                            <div class="car-info">
                                <h3><?= htmlspecialchars($car['brand']) ?><?= $car['model'] ? ' ' . htmlspecialchars($car['model']) : '' ?></h3>
                                <div class="car-category"><?= htmlspecialchars($car['category']) ?></div>
                                <div class="car-specs">
                                    <span><img src="assets/images/icons/gas-station.svg" alt=""><?= htmlspecialchars($car['fuel_capacity']) ?></span>
                                    <span><img src="assets/images/icons/car.svg" alt=""><?= htmlspecialchars($car['transmission']) ?></span>
                                    <span><img src="assets/images/icons/profile-2user.svg" alt=""><?= htmlspecialchars($car['capacity']) ?></span>
                                </div>
                                <div class="car-price">
                                    <?php if ($car['original_price']): ?>
                                        <span class="original-price">€<?= number_format($car['original_price'], 2, ',', '.') ?></span>
                                    <?php endif; ?>
                                    <span class="current-price">€<?= number_format($car['price'], 2, ',', '.') ?> / dag</span>
                                </div>
                                <a href="/car-detail?id=<?= $car['id'] ?>" class="button-primary">Bekijk nu</a>
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

<style>
.ons-aanbod {
    padding: 2rem 0;
}

.main-flex-container {
    display: flex;
    gap: 2rem;
    max-width: 1200px;
    margin: 0 auto;
    padding: 0 1rem;
}

.cars-section {
    flex: 3;
}

.filters-sidebar {
    flex: 1;
    min-width: 280px;
    max-width: 320px;
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.07);
    padding: 2rem 1.5rem;
    height: fit-content;
    align-self: flex-start;
}

.page-title {
    font-size: 2.5rem;
    margin-bottom: 2rem;
    text-align: left;
}

.filters {
    margin-bottom: 2rem;
}

.filter-buttons, .category-filters {
    display: flex;
    flex-direction: column;
    gap: 1rem;
    margin-bottom: 1.5rem;
}

.filter-btn, .category-btn {
    padding: 0.75rem 1.5rem;
    border: 1px solid #C3D4E9;
    border-radius: 10px;
    background: white;
    cursor: pointer;
    transition: all 0.3s ease;
    text-align: left;
    font-size: 0.95rem;
    color: #596780;
    position: relative;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.filter-btn::after, .category-btn::after {
    content: '';
    width: 20px;
    height: 20px;
    border: 2px solid #C3D4E9;
    border-radius: 4px;
    transition: all 0.3s ease;
}

.filter-btn:hover, .category-btn:hover {
    background: #F6F7F9;
    border-color: #3563E9;
    color: #3563E9;
}

.filter-btn:hover::after, .category-btn:hover::after {
    border-color: #3563E9;
}

.filter-btn.active, .category-btn.active {
    background: #3563E9;
    color: white;
    border-color: #3563E9;
}

.filter-btn.active::after, .category-btn.active::after {
    content: '✓';
    border: none;
    color: white;
    display: flex;
    align-items: center;
    justify-content: center;
    background: rgba(255, 255, 255, 0.2);
}

.filters h2 {
    font-size: 1.2rem;
    color: #1A202C;
    margin-bottom: 1rem;
}

.cars-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 2rem;
}

.car-card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    overflow: hidden;
    transition: transform 0.3s ease;
}

.car-card:hover {
    transform: translateY(-5px);
}

.car-image {
    width: 100%;
    height: 200px;
    overflow: hidden;
}

.car-image img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.car-info {
    padding: 1.5rem;
}

.car-info h3 {
    margin: 0 0 0.5rem;
    font-size: 1.25rem;
}

.car-category {
    display: inline-block;
    padding: 0.25rem 0.75rem;
    background: #f0f0f0;
    border-radius: 15px;
    font-size: 0.875rem;
    margin-bottom: 1rem;
}

.car-specs {
    display: flex;
    gap: 1rem;
    margin-bottom: 1rem;
    font-size: 0.875rem;
}

.car-specs span {
    display: flex;
    align-items: center;
    gap: 0.5rem;
}

.car-specs img {
    width: 16px;
    height: 16px;
}

.car-price {
    margin-bottom: 1rem;
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

.button-primary {
    display: block;
    width: 100%;
    text-align: center;
}

@media (max-width: 1024px) {
    .main-flex-container {
        flex-direction: column;
    }
    .filters-sidebar {
        max-width: 100%;
        width: 100%;
        margin-bottom: 2rem;
        padding: 1.5rem 1rem;
    }
    .cars-section {
        width: 100%;
    }
}

@media (max-width: 768px) {
    .cars-grid {
        grid-template-columns: 1fr;
    }
    .filters-sidebar {
        padding: 1rem 0.5rem;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const filterBtns = document.querySelectorAll('.filter-btn');
    const categoryBtns = document.querySelectorAll('.category-btn');
    const cars = document.querySelectorAll('.car-card');

    function filterCars() {
        const activeType = document.querySelector('.filter-btn.active').dataset.type;
        const activeCategories = Array.from(document.querySelectorAll('.category-btn.active'))
            .map(btn => btn.dataset.category);

        cars.forEach(car => {
            const matchesType = activeType === 'all' || car.dataset.type === activeType;
            const matchesCategory = activeCategories.length === 0 || 
                                  activeCategories.includes(car.dataset.category);
            
            car.style.display = matchesType && matchesCategory ? 'block' : 'none';
        });
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

