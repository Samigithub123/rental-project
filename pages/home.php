<?php require "includes/header.php" ?>
    <header>
        <div class="advertorials">
            <div class="advertorial">
                <h2>Hét platform om een auto te huren</h2>
                <p>Snel en eenvoudig een auto huren. Natuurlijk voor een lage prijs.</p>
                <a href="#" class="button-primary">Huur nu een auto</a>
                <img src="assets/images/car-rent-header-image-1.png" alt="">
                <img src="assets/images/header-circle-background.svg" alt="" class="background-header-element">
            </div>
            <div class="advertorial">
                <h2>Wij verhuren ook bedrijfswagens</h2>
                <p>Voor een vaste lage prijs met prettig voordelen.</p>
                <a href="#" class="button-primary">Huur een bedrijfswagen</a>
                <img src="assets/images/car-rent-header-image-2.png" alt="">
                <img src="assets/images/header-block-background.svg" alt="" class="background-header-element">

            </div>
        </div>
    </header>

    <main>
    <h2 class="section-title">Populaire auto's</h2>
    <div class="cars">
        <?php for ($i = 0; $i <= 3; $i++) : ?>
            <div class="car-details">
                <div class="car-brand">
                    <h3><?php
                    $carNames = [
                        0 => 'Koenigsegg Agera RS',
                        1 => 'Nissan GTR R35',
                        2 => 'Rolls Royce Wraith drophead',
                        3 => 'Nissan GTR R35'
                    ];
                    $carPrices = [
                        0 => 450.00,
                        1 => 249.00,
                        2 => 300.00,
                        3 => 249.00
                    ];
                    echo $carNames[$i] ?? 'Koenigsegg';
                    ?></h3>
                    <div class="car-type">
                        Sport
                    </div>
                </div>
                <img src="assets/images/products/car%20(<?= $i ?>).svg" alt="">
                <div class="car-specification">
                    <span><img src="assets/images/icons/gas-station.svg" alt="">90l</span>
                    <span><img src="assets/images/icons/car.svg" alt="">Schakel</span>
                    <span><img src="assets/images/icons/profile-2user.svg" alt="">2 Personen</span>
                </div>
                <div class="rent-details">
                    <span><span class="font-weight-bold">€<?= number_format($carPrices[$i] ?? 249.00, 2, ',', '.') ?></span> / dag</span>
                    <a href="/rental/car-detail?name=koenigsegg-<?= $i ?>" class="button-primary">Bekijk nu</a>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <h2 class="section-title">Aanbevolen auto's</h2>
    <div class="cars">
        <?php for ($i = 4; $i <= 11; $i++) : ?>
            <div class="car-details">
                <div class="car-brand">
                    <h3><?php
                    $carNames = [
                        4 => 'Koenigsegg CC850',
                        5 => 'Koenigsegg One:1',
                        6 => 'Koenigsegg CCX',
                        7 => 'Koenigsegg CCR',
                        8 => 'Koenigsegg Trevita',
                        9 => 'Koenigsegg CCXR',
                        10 => 'Koenigsegg Hundra',
                        11 => 'Koenigsegg Agera R'
                    ];
                    $carPrices = [
                        4 => 329.00,
                        5 => 399.00,
                        6 => 289.00,
                        7 => 279.00,
                        8 => 359.00,
                        9 => 299.00,
                        10 => 369.00,
                        11 => 339.00
                    ];
                    echo $carNames[$i] ?? 'Koenigsegg';
                    ?></h3>
                    <div class="car-type">
                        Sport
                    </div>
                </div>
                <img src="assets/images/products/car%20(<?= $i ?>).svg" alt="">
                <div class="car-specification">
                    <span><img src="assets/images/icons/gas-station.svg" alt="">90l</span>
                    <span><img src="assets/images/icons/car.svg" alt="">Schakel</span>
                    <span><img src="assets/images/icons/profile-2user.svg" alt="">2 Personen</span>
                </div>
                <div class="rent-details">
                    <span><span class="font-weight-bold">€<?= number_format($carPrices[$i] ?? 249.00, 2, ',', '.') ?></span> / dag</span>
                    <a href="/rental/car-detail?name=koenigsegg-<?= $i ?>" class="button-primary">Bekijk nu</a>
                </div>
            </div>
        <?php endfor; ?>
    </div>
    <div class="show-more">
        <a class="button-primary" href="#">Toon alle</a>
    </div>
    </main>

<?php require "includes/footer.php" ?>