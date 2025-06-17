<?php
/**
 * Services component
 */

// Define service items
$serviceItems = [
    [
        'icon' => 'assets/images/service-01.png',
        'title' => 'Eventy na mieru',
        'description' => 'S každým zákaznikom si prejdeme jeho predstavu a očakávania. Pretože nám záleží.',
    ],
    [
        'icon' => 'assets/images/service-02.png',
        'title' => 'Rýchlosť',
        'description' => 'Veríme v kvalitnú prácu. Rýchlosť je len výhodou, ktorú ponúkame svojim zákaznikom. Pretože nám záleží.',
    ],
    [
        'icon' => 'assets/images/service-03.png',
        'title' => 'Kvalita',
        'description' => 'Pravdilne sa vzdelávame v oblasti, aby sme mohli priniesť ešte viac. Pretože nám záleží.',
    ]
];
?>

<!-- Services Section -->
<div class="services section" id="services">
    <div class="container">
        <div class="row">

            <?php foreach ($serviceItems as $service) : ?>
                <div class="col-lg-4 col-md-6">
                    <div class="service-item">
                        <div class="icon">
                            <img src="<?php echo $service['icon']; ?>" alt="<?php echo $service['title']; ?>">
                        </div>
                        <div class="main-content">
                            <h4><?php echo $service['title']; ?></h4>
                            <p><?php echo $service['description']; ?></p>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
</div>