<?php require "includes/header.php" ?>

<?php
$name = $_GET['name'] ?? null;

if (!$name) {
    header('Location: /rental/404');
    exit;
}

$carInfo = [
    'koenigsegg-0' => [
        'title' => 'Koenigsegg Agera RS',
        'description' => 'Een van de snelste productieauto\'s ter wereld met ongeëvenaarde prestaties en luxe.',
        'type' => 'Sport',
        'capacity' => '2 personen',
        'steering' => 'Automaat',
        'gasoline' => '90L',
        'price' => '450,00',
        'image' => 'assets/images/products/car (0).svg'
    ],
    'koenigsegg-1' => [
        'title' => 'Nissan GTR R35',
        'description' => 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.',
        'type' => 'Sport',
        'capacity' => '2 personen',
        'steering' => 'Automaat',
        'gasoline' => '95L',
        'price' => '249,00',
        'image' => 'assets/images/products/car (1).svg'
    ],
    'koenigsegg-2' => [
        'title' => 'Rolls Royce Wraith drophead',
        'description' => 'De ultieme luxe cabriolet met ongeëvenaarde elegantie en comfort.',
        'type' => 'Luxe',
        'capacity' => '4 personen',
        'steering' => 'Automaat',
        'gasoline' => '85L',
        'price' => '300,00',
        'image' => 'assets/images/products/car (2).svg'
    ],
    'koenigsegg-3' => [
        'title' => 'Nissan GTR R35',
        'description' => 'De GTR R35 is de opvolger van de GTR R34, met nog meer vermogen en innovatieve technologie.',
        'type' => 'Sport',
        'capacity' => '2 personen',
        'steering' => 'Automaat',
        'gasoline' => '82L',
        'price' => '249,00',
        'image' => 'assets/images/products/car (3).svg'
    ],
    'koenigsegg-4' => [
        'title' => 'Koenigsegg CC850',
        'description' => 'Een eerbetoon aan de klassieke CC8S met moderne technologie en prestaties.',
        'type' => 'Sport',
        'capacity' => '2 personen',
        'steering' => 'Handgeschakeld',
        'gasoline' => '90L',
        'price' => '329,00',
        'image' => 'assets/images/products/car (4).svg'
    ]
];

$car = $carInfo[$name] ?? null;

if (!$car) {
    header('Location: /rental/404');
    exit;
}
?>
<main class="car-detail">
    <div class="grid">
        <div class="row">
            <div class="advertorial">
                <h2><?php echo htmlspecialchars($car['title']); ?></h2>
                <p><?php echo htmlspecialchars($car['description']); ?></p>
                <img src="<?php echo htmlspecialchars($car['image']); ?>" alt="<?php echo htmlspecialchars($car['title']); ?>">
                <img src="assets/images/header-circle-background.svg" alt="" class="background-header-element">
            </div>
        </div>
        <div class="row white-background">
            <h2><?php echo htmlspecialchars($car['title']); ?></h2>
            <div class="rating">
                <span class="stars stars-4"></span>
                <span>440+ reviewers</span>
            </div>
            <p><?php echo htmlspecialchars($car['description']); ?></p>
            <div class="car-type">
                <div class="grid">
                    <div class="row"><span class="accent-color">Type Auto</span><span><?php echo htmlspecialchars($car['type']); ?></span></div>
                    <div class="row"><span class="accent-color">Capaciteit</span><span><?php echo htmlspecialchars($car['capacity']); ?></span></div>
                </div>
                <div class="grid">
                    <div class="row"><span class="accent-color">Versnelling</span><span><?php echo htmlspecialchars($car['steering']); ?></span></div>
                    <div class="row"><span class="accent-color">Brandstof</span><span><?php echo htmlspecialchars($car['gasoline']); ?></span></div>
                </div>
                <div class="call-to-action">
                    <div class="row"><span class="font-weight-bold">€<?php echo htmlspecialchars($car['price']); ?></span> / dag</div>
                    <div class="row"><a href="/rental/reservation?car=<?php echo urlencode($name); ?>" class="button-primary">Huur nu</a></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require "includes/footer.php" ?>
